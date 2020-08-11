<?php

namespace App\Controller;

use App\Entity\NiveauEvaluation;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\GroupeCompetenceRepository;
use App\Repository\NiveauEvaluationRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function editCompetence(int $id, NiveauEvaluationRepository $repoNiveau, CompetenceRepository $repoComp, GroupeCompetenceRepository $repoGroupeComp, EntityManagerInterface $em, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $competence = $repoComp->find($id);
        
        if (empty($data['libelle'])) {
            return new JsonResponse('Le libelle est requis.', Response::HTTP_BAD_REQUEST, [], true);
        }

        $niveaux = $data['niveaux'];
        if (count($niveaux) != 3) {
            return new JsonResponse("Trois niveaux d'évaluation sont requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        
        $competence->setLibelle($data['libelle']);
        
        foreach ($competence->getGroupeCompetences() as $value) {
            $competence->removeGroupeCompetence($value);
        }

        for ($i = 0; $i < count($data["groupeCompetences"]); $i++) {
            $grpComp = $repoGroupeComp->findBy(array('libelle' => $data["groupeCompetences"][$i]["libelle"]));
            if (!is_null($grpComp)) {
                $competence->addGroupeCompetence($grpComp[0]);
            }
        }

        if (count($competence->getGroupeCompetences()) < 1) {
            return new JsonResponse("Veuillez renseigner au moins un groupe de compétences existant.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $tabLibelle = [];
        foreach ($competence->getNiveaux() as $value) {
            $competence->removeNiveau($value);
        }

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
            return new JsonResponse("Le libellé, le groupe d'action et le critère d'évaluation d'un niveau sont requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($competence);
        $em->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
}
