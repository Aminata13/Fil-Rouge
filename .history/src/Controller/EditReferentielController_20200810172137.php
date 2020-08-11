<?php

namespace App\Controller;

use App\Entity\Referentiel;
use App\Entity\CritereAdmission;
use App\Entity\CritereEvaluation;
use App\Repository\CritereAdmissionRepository;
use App\Repository\CritereEvaluationRepository;
use App\Repository\GroupeCompetenceRepository;
use App\Repository\ReferentielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditReferentielController extends AbstractController
{
    /**
     * @Route(
     *     name="edit_referentiel",
     *     path="/api/admin/referentiels/{id}",
     *     methods={"PUT"}
     * )
     */
    public function editReferentiel(Request $request, int $id, ReferentielRepository $repoReferentiel, GroupeCompetenceRepository $repoGroupeComp, EntityManagerInterface $em, CritereAdmissionRepository $repoAdmisssion, CritereEvaluationRepository $repoEvaluation)
    {
        $data = $request->request->all();

        $referentiel = $repoReferentiel->find($id);
        if(is_null($referentiel)) {
            return new JsonResponse("Ce référentiel n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
        }

        /**Archivage */
        if(isset($data['deleted']) && $data['deleted']) {
            $referentiel->setDeleted(true);
            return new JsonResponse('Référentiel archivé.', Response::HTTP_NO_CONTENT, [], true);
        }

        /**Modification */
        if (empty($data['libelle'])) {
            return new JsonResponse('Le libelle est requis.', Response::HTTP_BAD_REQUEST, [], true);
        }
        if (empty($data['description'])) {
            return new JsonResponse('La présentation est requise.', Response::HTTP_BAD_REQUEST, [], true);
        }
        if (empty($data['critereAdmissions'])) {
            return new JsonResponse("Un critère d'admission est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }
        if (empty($data['critereEvaluations'])) {
            return new JsonResponse("Un critère d'évaluation est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }
        if (empty($data['groupeCompetences'])) {
            return new JsonResponse("Un groupe de compétences est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }


        $tabCritereAdmission = $referentiel->getCritereAdmissions();
        foreach ($tabCritereAdmission as $value) {
            $referentiel->removeCritereAdmission($value);
        }

        $tabCritereEvaluation = $referentiel->getCritereEvaluations();
        foreach ($tabCritereEvaluation as $value) {
            $referentiel->removeCritereEvaluation($value);
        }

        $tabGroupeCompetence = $referentiel->getGroupeCompetences();
        foreach ($tabGroupeCompetence as $value) {
            $referentiel->removeGroupeCompetence($value);
        }

        /**Partie ajout de la modification*/
        $tabLibelle = [];
        foreach ($data['critereAdmissions'] as $value) {
            if ($value != "") {
                $critereAdmission = $repoAdmisssion->findBy(array('libelle' => $value));
                if ($critereAdmission) {
                    $referentiel->addCritereAdmission($critereAdmission[0]);
                } else {
                    if (!in_array($value, $tabLibelle)) {
                        $tabLibelle[] = $value;
                        $critereAdmission = new CritereAdmission();
                        $critereAdmission->setLibelle($value);
                        $referentiel->addCritereAdmission($critereAdmission);
                    }
                }
            }
        }
        if (count($referentiel->getCritereAdmissions()) < 1) {
            return new JsonResponse("Le libelle du critère d'admission est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $tabLibelle = [];
        foreach ($data['critereEvaluations'] as $value) {
            if ($value != "") {
                $critereEvaluation = $repoEvaluation->findBy(array('libelle' => $value));
                if ($critereEvaluation) {
                    $referentiel->addCritereEvaluation($critereEvaluation[0]);
                } else {
                    if (!in_array($value, $tabLibelle)) {
                        $tabLibelle[] = $value;
                        $critereEvaluation = new CritereEvaluation();
                        $critereEvaluation->setLibelle($value);
                        $referentiel->addCritereEvaluation($critereEvaluation);
                    }
                }
            }
        }
        if (count($referentiel->getCritereEvaluations()) < 1) {
            return new JsonResponse("Le libelle du critère d'évaluation est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        foreach ($data['groupeCompetences'] as $value) {
            if ($value != "") {
                $groupeCompetence = $repoGroupeComp->findBy(array('libelle' => $value));
                if (!empty($groupeCompetence)) {
                    $referentiel->addGroupeCompetence($groupeCompetence[0]);
                }
            }
        }
        if (count($referentiel->getGroupeCompetences()) < 1) {
            return new JsonResponse("Un groupe de compétence existant est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $file = $request->files;
        if (is_null($file->get('programme'))) {
            return new JsonResponse("Le programme est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }
        $fileType = explode("/", $file->get('programme')->getMimeType())[1];
        $filePath = $file->get('programme')->getRealPath();

        $programme = file_get_contents($filePath, 'pdf/pdf.' . $fileType);
        $referentiel->setProgramme($programme);
        $referentiel->setLibelle($data["libelle"]);
        $referentiel->setDescription($data["description"]);

        
        $em->persist($referentiel);
        $em->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
}
