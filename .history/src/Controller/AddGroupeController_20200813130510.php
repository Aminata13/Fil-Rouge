<?php

namespace App\Controller;

use DateTime;
use App\Entity\Groupe;
use App\Repository\GroupeRepository;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddGroupeController extends AbstractController
{
    private $em;
    private $repo;
    private $validator;
    private $repoPromo;
    private $repoFormateur;
    private $repoApprenant;


    public function __construct(PromotionRepository $repoPromo, FormateurRepository $repoFormateur, ApprenantRepository $repoApprenant, GroupeRepository $repo, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->repo = $repo;
        $this->validator = $validator;
        $this->repoPromo = $repoPromo;
        $this->repoFormateur = $repoFormateur;
        $this->repoApprenant = $repoApprenant;
    }

    /**
     * @Route(
     *     name="add_groupe",
     *     path="/api/admin/groupes",
     *     methods={"POST"},
     * )
     */
    public function addGroupe(Request $request)
    {
        $data = json_decode($request->getContent(), true);


        if (!isset($data['promotion']) || empty($data['promotion'])) {
            return new JsonResponse("Une promotion est requise pour la création d'un groupe.", Response::HTTP_BAD_REQUEST, [], true);
        }
        if (!isset($data['apprenants']) || count($data['apprenants']) < 1) {
            return new JsonResponse("Veuillez renseigner les apprenants du groupe.", Response::HTTP_BAD_REQUEST, [], true);
        }
        if (!isset($data['formateurs']) || count($data['formateurs']) < 1) {
            return new JsonResponse("Veuillez renseigner les formateurs du groupe.", Response::HTTP_BAD_REQUEST, [], true);
        }
        // Traitement Groupe
        $groupe = new Groupe();
        $groupe->setLibelle($data['libelle']);
        $groupe->setDateCreation(new \DateTime());
        $groupe->setPromotion($this->repoPromo->find($data['promotion']));

        // Traitement apprenants
        foreach ($data['apprenants'] as $value) {
            $apprenant = $this->repoApprenant->find($value['id']);
            if ($apprenant) {
                $groupe->addApprenant($apprenant);
            }
        }

        // Traitement formateurs
        foreach ($data['formateurs'] as $value) {
            $formateur = $this->repoFormateur->find($value['id']);
            if ($formateur) {
                $groupe->addFormateur($formateur);
            }
        }


        $errors = $this->validator->validate($groupe);
        if (($errors) > 0) {
            $errorsString = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }



        $this->em->persist($groupe);
        $this->em->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route(
     *     name="edit_groupe",
     *     path="/api/admin/groupes/{id}",
     *     methods={"PUT"},
     * )
     */
    public function EditGroupe(int $id, Request $request,   GroupeRepository $repoGroupe)
    {
        $data = json_decode($request->getContent(), true);


        
        if (!isset($data['apprenants']) || count($data['apprenants']) < 1) {
            return new JsonResponse("Veuillez renseigner les apprenants du groupe.", Response::HTTP_BAD_REQUEST, [], true);
        }
        if (!isset($data['formateurs']) || count($data['formateurs']) < 1) {
            return new JsonResponse("Veuillez renseigner les formateurs du groupe.", Response::HTTP_BAD_REQUEST, [], true);
        }
        // Traitement Groupe
        $groupe = $repoGroupe->find($id) ;
        if (isset($data['libelle']) || !empty($data['libelle'])) {
            $groupe->setLibelle($data['libelle']);
        }
        if (isset($data['promotion']) || !empty($data['promotion'])) {
            $groupe->setPromotion($this->repoPromo->find($data['promotion']));
        }
        

        // Traitement apprenants
        foreach ($groupe->getApprenants() as $value) {
            $groupe->removeApprenant($value);
        }

        foreach ($data['apprenants'] as $value) {
            $apprenant = $this->repoApprenant->find($value['id']);
            if ($apprenant) {
                $groupe->addApprenant($apprenant);
            }
        }

        // Traitement formateurs
        foreach ($groupe->getFormateurs() as $value) {
            $groupe->removeFormateur($value);
        }

        foreach ($data['formateurs'] as $value) {
            $formateur = $this->repoFormateur->find($value['id']);
            if ($formateur) {
                $groupe->addFormateur($formateur);
            }
        }


        $errors = $this->validator->validate($groupe);
        if (($errors) > 0) {
            $errorsString = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }



        $this->em->persist($groupe);
        $this->em->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route(
     *     name="delete_apprenant_groupe",
     *     path="/api/admin/groupes/{id_groupe}/apprenants/{id_apprenant}",
     *     methods={"PUT"},
     * )
     */
    public function deleteApprenantGroupe(int $id_groupe, int $id_apprenant, Request $request,   GroupeRepository $repoGroupe)
    {
        
        // Traitement Groupe
        $groupe = $repoGroupe->find($id_groupe) ;
        

        // Traitement apprenants
        foreach ($groupe->getApprenants() as $value) {
            $groupe->removeApprenant($value);
        }

        foreach ($data['apprenants'] as $value) {
            $apprenant = $this->repoApprenant->find($value['id']);
            if ($apprenant) {
                $groupe->addApprenant($apprenant);
            }
        }

        $errors = $this->validator->validate($groupe);
        if (($errors) > 0) {
            $errorsString = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }



        $this->em->persist($groupe);
        $this->em->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
}
