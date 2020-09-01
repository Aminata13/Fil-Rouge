<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Apprenant;
use App\Repository\ApprenantRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="add_user", methods="POST")
     */
    public function addUser(SerializerInterface $serializer, Request $request, ValidatorInterface $validator, EntityManagerInterface $em, UserProfilRepository $repo, UserPasswordEncoderInterface $encoder)
    {
        $currentUser = $this->getUser();
        if (!in_array("ROLE_ADMIN", $currentUser->getRoles())) {
            return new JsonResponse('Vous n\'avez pas accès à cette ressource.', Response::HTTP_FORBIDDEN, [], true);
        }

        $userTab = $request->request->all();

        if (empty($userTab['profil'])){
            return new JsonResponse("Le profil est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }

        $profilId = explode("/", $userTab['profil'])[2];
        $profil = $repo->find($profilId);

        $libelle = $profil->getLibelle();
        
        $object = "";
        if ($libelle == "APPRENANT") {
            $object = new Apprenant();
        }
        
        unset($userTab['profil']);
        
        $user = $serializer->denormalize($userTab, User::class, true);
        $user->setProfil($profil);
        $user->setPassword($encoder->encodePassword($user, $userTab['password']));

        $avatar = $request->files;
        if (is_null($avatar->get('avatar'))) {
            return new JsonResponse("L'avatar est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $avatarType = explode("/", $avatar->get('avatar')->getMimeType())[1];
        $avatarPath = $avatar->get('avatar')->getRealPath();

        $image = file_get_contents($avatarPath, 'img/img.' . $avatarType);
        $user->setAvatar($image);

        $errors = $validator->validate($user);
        if (($errors) > 0) {
            $errorsString = $serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }
        $object->setUser($user);
        if ($object != "") {
            $em->persist($user);
            $em->persist($object);
            $em->flush();
        }

        return new JsonResponse('success', Response::HTTP_CREATED, [], true);
    }


    /**
     * @Route("/user", name="get_user_connecte", methods="GET")
     */
    public function getUserConnecter(SerializerInterface $serializer,ApprenantRepository $repoApre)
    {
        $user = $this->getUser();
        $aprenant = $repoApre->findOneByIdUser($user->getId());
        if (!$aprenant) {
            $userJson = $serializer->serialize(["msg"=>"l'apprenant existe pas"], 'json');
            return new JsonResponse($userJson, Response::HTTP_OK, [], true);
        }

        $userJson = $serializer->serialize(["attente"=>$aprenant->getAttente(),"user"=>$user], 'json');
        return new JsonResponse($userJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/user", name="post_update_apprenast", methods="POST")
     */
    public function updateInfoapprenant(EntityManagerInterface $em, SerializerInterface $serializer,Request $request,ApprenantRepository $repoApre)
    {
        $user = $this->getUser();
        $aprenant = $repoApre->findOneByIdUser($user->getId());
        if (!$aprenant){
            $userJson = $serializer->serialize(["msg"=>"l'apprenant existe pas"], 'json');
            return new JsonResponse($userJson, Response::HTTP_OK, [], true);
        }
        $data = json_decode($request->getContent(),true);
        if (count($data)<1) {
            
        }
        $user->setUsername($data['username']);
        $user->setPassword($data['password']);
        $user->setFirstname($data['prenom']);
        $user->setLastname($data['nom']);
        $aprenant->setAttente(false);

        // $em->persist($user);
        // $em->persist($aprenant);
        $em->flush();
        $userJson = $serializer->serialize(["response"=>"su"],'json');
        return new JsonResponse($userJson, Response::HTTP_OK, [], true);
    }
}
