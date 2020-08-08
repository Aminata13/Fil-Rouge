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
use App\Entity\NiveauEvaluation;
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
    public function editCompetence(int $id, NiveauEvaluationRepository $repoNiveau, CompetenceRepository $repoCompe, GroupeCompetenceRepository $repoGroupCompe, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['libelle'])) {
            return new JsonResponse('Le libelle est requis.', Response::HTTP_BAD_REQUEST, [], true);
        }

        $niveaux = $data['niveaux'];
        if (count($niveaux) != 3) {
            return new JsonResponse("Un niveau est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $competence = $repoCompe->find($id);
        foreach ($competence->getGroupeCompetences() as $value) {
            $competence->removeGroupeCompetence($value);
        }

        $tabNiveau = $competence->getNiveaux();
        foreach ($tabNiveau as $value) {
            $competence->removeNiveau($value);
        }
        $grpComp = $repoGroupCompe->findBy(array('libelle' => $data["groupeCompetences"][0]["libelle"]));
        
        if (empty($grpComp)) {
            return new JsonResponse("Ce groupe de competence n\'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
        }
        $competence->
        $competence->setLibelle($data['libelle']);

        $tabLibelle = [];
        //dd($data['competences']);
        foreach ($data['niveaux'] as $value) {

            if (!empty($value['libelle']) && !empty($value["groupeAction"]) && !empty($value["critereEvaluation"])) {
                $niveau = $repoNiveau->findBy(array('libelle' => $value['libelle']));
                if ($niveau) {
                    $competence->addNiveau($niveau[0]);
                } else {
                    if (!in_array($value['libelle'], $tabLibelle)) {
                        $tabLibelle[] = $value['libelle'];
                        $niveau = new NiveauEvaluation();
                        $niveau->setLibelle($value['libelle']);
                        $niveau->setGroupeAction($value["groupeAction"]);
                        $niveau->setCritereEvaluation($value["critereEvaluation"]);
                        $competence->addNiveau($niveau);
                    }
                }
            }
        }

        if (count($competence->getNiveaux()) < 1) {
            return new JsonResponse("Un niveau est requise.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($competence);
        $em->flush();
        return new JsonResponse("succes", Response::HTTP_CREATED, [], true);
    }
}
