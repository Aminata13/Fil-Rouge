<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Apprenant;
use App\Entity\Promotion;
use App\Repository\UserProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Repository\FabriqueRepository;
use App\Repository\LangueRepository;
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
    public function addUser(LangueRepository $repoLangue,FabriqueRepository $repoFabrique,SerializerInterface $serializer, Request $request, ValidatorInterface $validator, EntityManagerInterface $em, UserProfilRepository $repo, UserPasswordEncoderInterface $encoder)
    {

        $promotionTab = $request->request->all();
        
        $promotion = $serializer->denormalize($promotionTab, Promotion::class, true,[eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1OTcxNTMxNjUsImV4cCI6MTU5NzE1Njc2NSwicm9sZXMiOlsiUk9MRV9BRE1JTiJdLCJ1c2VybmFtZSI6ImFkbWluMSJ9.DRdLwkng1lJ-cB_q9FcHODY2SKUFR9gdnFgN5t9xIM_tX3LEFpeDag6uOQJVDPoR-A-z5shFTr2FC84iRHTyANCbNppjCqxqaw4K-AOXFiOejeevlx2syZzzf8MBqTvD87mlBt2wbOtKFDx2K9rBccSQNsBxQH3iRRpDsqCOO3EbxCPwSb5-3iPOOSbnQt6rrKzAPgnnjX9ZLiYxxrEJ9FXPvVCtnXMhAW4mj0OBEdI4Fnfn6Y4BWDwQ-sHNq5Rndq1m0Gy1sy760RxrF_CpIKWRTolflB9-LktEi78o57RDx_9j33hY6dNyWwHp_RsVUcwsln-d55WCWbbsxi4z5Ga5npRe0xeoDgw-Qg1evqlFa_aUy2TeWT2Gsdty9zKOSqUxjcuQT_iJQQdv43QmRyZQ2C9PquJlb6udk1M-QsuT1M0pwYWj0zYtAEdvqA8rDCJHjG9QLZihV6MdYN4dnuqomrDawNoa9N8ThY5jFAM1jUrUD2xJRI0ey9of2IyYRVj3mWQU7EIFvfHoRQAjZks3vVjx4Ysh2SrJkVZ6JSJzNHsUcC2Jac6_cjO-HOmPI7nDuKOOVdGmILnq7PB6bMTGB1C3WtgWUbkXhf8JBJZXroD694HLsYb5805pm8mjeFJnOeyq8e9Ic9jzefX5xx3vZCTpF1XwK_xWNQwXU-]);

        dd($promotion);

        if (empty($promotionTab['langue'])) {
            return new JsonResponse("La langue est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        
        //$promotion->setLangue();
        dd($promotion);
        if (empty($promotionTab['fabrique'])) {
            return new JsonResponse("La langue est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $promotion->setFabrique($repoFabrique->findBy(array('libelle' => $promotionTab['fabrique']))[0]);

        // Traitement Image
        $image = $request->files;
        if (is_null($image->get('image'))) {
            return new JsonResponse("L'image est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $imageType = explode("/", $image->get('image')->getMimeType())[1];
        $imagePath = $image->get('image')->getRealPath();

        $image = file_get_contents($imagePath, 'img/img.' . $imageType);
        $promotion->setimage($image);
        
        

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

        $image = $request->files;
        if (is_null($image->get('image'))) {
            return new JsonResponse("L'image est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $imageType = explode("/", $image->get('image')->getMimeType())[1];
        $imagePath = $image->get('image')->getRealPath();

        $image = file_get_contents($imagePath, 'img/img.' . $imageType);
        $user->setimage($image);

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
