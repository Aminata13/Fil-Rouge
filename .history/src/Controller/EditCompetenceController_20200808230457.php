<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Entity\GroupeCompetence;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Repository\GroupeCompetenceRepository;
use App\Repository\NiveauEvaluationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditCompetenceController extends AbstractController
{
    /**
     * @Route(
     *     name="edit_competence",
     *     path="/api/admin/competences/{id}",
     *     methods={"PUT"}
     * )
     */
    public function editGroupeCompetence(int $id ,NiveauEvaluationRepository $repoNiveau,CompetenceRepository $repoCompe,GroupeCompetenceRepository $repoGroupCompe, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator,Request $request)
    {
        $data=json_decode($request->getContent(),true);
        
        if (empty($data['libelle'])) {
            return new JsonResponse('Le libelle est requis.', Response::HTTP_BAD_REQUEST, [], true);
        }

        $niveaux = $data['niveaux'];
        if (count($niveaux) != 3) {
            return new JsonResponse("Un niveau est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }
        
        $competence = $repoCompe->find($id);
       
        $tabNiveau = $competence->getNiveaux();
        
        foreach ($tabNiveau as $value) {
            $competence->removeNiveau($value);
        }

        $competence->setLibelle($data['libelle']);
        
        $tabLibelle = [];
        //dd($data['competences']);
        foreach ($data['niveaux'] as $value){
            
            if (!empty($value['libelle'])){
                $niveau = $repoNiveau->findBy(array('libelle' => $value['libelle']));
                if ($niveau) {
                    $competence->addNiveau($niveau[0]);
                } else {
                    if (!in_array($value['libelle'], $tabLibelle)) {
                        $tabLibelle[] = $value['libelle'];
                        $niveau = new Ni();
                        $competence->setLibelle($value['libelle']);
                        $groupeCompetence->addCompetence($competence);
                    }
                }
            }
        }

        if (count($groupeCompetence->getCompetences())<1) {
            return new JsonResponse("Une compétence est requise.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($groupeCompetence);
        $em->flush();
        return new JsonResponse("succes", Response::HTTP_CREATED, [], true);
    }
}
