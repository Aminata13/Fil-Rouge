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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api")
 */
class AddPromotionController extends AbstractController
{
    /**
     * @Route("/admin/promotion", name="add_promotion", methods="POST")
     */
    public function addUser(SerializerInterface $serializer, Request $request, ValidatorInterface $validator, EntityManagerInterface $em, UserProfilRepository $repo, UserPasswordEncoderInterface $encoder)
    {
        $userTab = $request->request->all();


        // Traitement Image
        $i = $request->files;
        if (is_null($i->get('i'))) {
            return new JsonResponse("L'i est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $iType = explode("/", $i->get('i')->getMimeType())[1];
        $iPath = $i->get('i')->getRealPath();

        $image = file_get_contents($iPath, 'img/img.' . $iType);
        $user->seti($image);
        
        dd($userTab);

        $currentUser = $this->getUser();
        if (!in_array("ROLE_ADMIN", $currentUser->getRoles())) {
            return new JsonResponse('Vous n\'avez pas accès à cette ressource.', Response::HTTP_FORBIDDEN, [], true);
        }

        

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

        $i = $request->files;
        if (is_null($i->get('i'))) {
            return new JsonResponse("L'i est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $iType = explode("/", $i->get('i')->getMimeType())[1];
        $iPath = $i->get('i')->getRealPath();

        $image = file_get_contents($iPath, 'img/img.' . $iType);
        $user->seti($image);

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
}
