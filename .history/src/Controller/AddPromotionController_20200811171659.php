<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Groupe;
use App\Entity\Apprenant;
use App\Entity\Promotion;
use App\Repository\LangueRepository;
use App\Repository\FabriqueRepository;
use App\Repository\UserProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Repository\ReferentielRepository;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
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
    public function addUser(ReferentielRepository $reporef,LangueRepository $repoLangue,FabriqueRepository $repoFabrique,SerializerInterface $serializer, Request $request, ValidatorInterface $validator, EntityManagerInterface $em, UserProfilRepository $repo, UserPasswordEncoderInterface $encoder)
    {

        $promotionTab = $request->request->all();

       
        
        $promotion = $serializer->denormalize($promotionTab, Promotion::class, true,["groups"=>"promotion:write"]);
        
        // Traitement Langue
        if (empty($promotionTab['langue'])) {
            return new JsonResponse("La langue est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $promotion->setLangue($repoLangue->findBy(array('libelle' => $promotionTab['langue']))[0]);
        
        // Traitement Fabrique
        if (empty($promotionTab['fabrique'])) {
            return new JsonResponse("La langue est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $promotion->setFabrique($repoFabrique->findBy(array('libelle' => $promotionTab['fabrique']))[0]);
        
        // Traitement Groupes
        if (empty($promotionTab['groupes'])) {
            return new JsonResponse("Le groupe est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $groupe = new Groupe();
        $groupe->setLibelle($promotionTab['groupes']);
        $groupe->setDateCreation(new \DateTime());
        $promotion->addGroupe($groupe);

        // Traitement referentiels
        foreach ($promotionTab['referentiels'] as $value){
            if (!empty($value)){               
                $referentiel = $reporef->findBy(array('libelle' =>$value));
                $promotion->addReferentiel($referentiel[0]);
            }
        }

        // Traitement Image
        $image = $request->files;
        if (is_null($image->get('image'))) {
            return new JsonResponse("L'image est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $imageType = explode("/", $image->get('image')->getMimeType())[1];
        $imagePath = $image->get('image')->getRealPath();

        $image = file_get_contents($imagePath, 'img/img.' . $imageType);
        $promotion->setimage($image);
        // Traitement Image
        dd($promotion);
    
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
