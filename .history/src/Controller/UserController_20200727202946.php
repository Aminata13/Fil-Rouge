<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
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
        if(!in_array("ROLE_ADMIN", $currentUser->getRoles())){
            return new JsonResponse('Vous n\'avez pas accès à cette ressource.', Response::HTTP_FORBIDDEN, [], true);
        }

        $userTab = $request->request->all();
        $profilId = explode("/", $userTab['profil'])[2];
        $profil = $repo->find($profilId);
        unset($userTab['profil']);
        $user = $serializer->denormalize($userTab, User::class, true);
        $user->setProfil($profil);
        $user->setPassword($encoder->encodePassword($user, $userTab['password']));

        $avatar = $request->files;
        $avatarType = explode("/", $avatar->get('avatar')->getMimeType())[1];
        $avatarPath = $avatar->get('avatar')->getRealPath();
        
        $image = file_get_contents($avatarPath, 'img/img.'.$avatarType);
        $user->setAvatar($image);
        
        $errors = $validator->validate($user);
        if (($errors)>0) {
            $errorsString = $serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($user);
        $em->flush();
        
        return new JsonResponse('success', Response::HTTP_CREATED, [], true);
    }
}
