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


    public function __construct(PromotionRepository $repoPromo,FormateurRepository $repoFormateur,ApprenantRepository $repoApprenant,GroupeRepository $repo, EntityManagerInterface $em, ValidatorInterface $validator)
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
        $data=json_decode($request->getContent(),true);
       

        if ( !isset($data['promotion']) || empty($data['promotion'])) {
            return new JsonResponse("Une promotion est requise pour la création d'un groupe.", Response::HTTP_BAD_REQUEST, [], true);
        }
        if ( !isset($data['apprenants']) || count($data['apprenants'])<1) {
            return new JsonResponse("Veuillez renseigner les apprenants du groupe.", Response::HTTP_BAD_REQUEST, [], true);
        }
        if ( !isset($data['formateurs']) || count($data['formateurs'])<1) {
            return new JsonResponse("Veuillez renseigner les formateurs du groupe.", Response::HTTP_BAD_REQUEST, [], true);
        }
        // Traitement Groupe
        $groupe = new Groupe();
        $groupe->setLibelle($data['libelle']);
        $groupe->setDateCreation(new \DateTime());
        $groupe->setPromotion($this->repoPromo->find($data['promotion']));
        //dd($groupe);

        foreach ($data['apprenants'] as $value) {
           $apprenant=$this->repoApprenant->find($value['id']);
           
        }

        $errors = $this->validator->validate($data);
        if (($errors) > 0) {
            $errorsString = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }
        


        

        // $this->em->persist();
        // $this->em->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
}
