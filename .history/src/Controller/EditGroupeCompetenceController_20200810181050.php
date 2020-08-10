<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditGroupeCompetenceController extends AbstractController
{
    /**
     * @Route(
     *     name="edit_groupe_competence",
     *     path="/api/admin/groupe_competences/{id}",
     *     methods={"PUT"}
     * )
     */
    public function editGroupeCompetence(int $id, CompetenceRepository $repoComp, GroupeCompetenceRepository $repoGroupeComp, EntityManagerInterface $em, Request $request)
    {
        $data=json_decode($request->getContent(),true);
        
        if(is_null($competence)) {
            return new JsonResponse("Cette compétence n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
        }

        /**Archivage */
        if(isset($data['deleted']) && $data['deleted']) {
            $competence->setDeleted(true);
            return new JsonResponse('Compétence archivé archivé.', Response::HTTP_NO_CONTENT, [], true);
        }

        if (empty($data['libelle'])) {
            return new JsonResponse('Le libelle est requis.', Response::HTTP_BAD_REQUEST, [], true);
        }

        $competences = $data['competences'];
        if (count($competences) < 1) {
            return new JsonResponse("Une compétence est requise.", Response::HTTP_BAD_REQUEST, [], true);
        }
        
       
        $tabCompetence = $groupeCompetence->getCompetences();
        
        foreach ($tabCompetence as $value) {
            $groupeCompetence->removeCompetence($value);
        }

        $groupeCompetence->setLibelle($data['libelle']);
        $groupeCompetence->setDescription($data['description']);
        
        $tabLibelle = [];
        foreach ($data['competences'] as $value){ 
            if (!empty($value['libelle'])){
                $competence = $repoComp->findBy(array('libelle' => $value['libelle']));
                if ($competence) {
                    $groupeCompetence->addCompetence($competence[0]);
                } else {
                    if (!in_array($value['libelle'], $tabLibelle)) {
                        $tabLibelle[] = $value['libelle'];
                        $competence = new Competence();
                        $competence->setLibelle($value['libelle']);
                        $groupeCompetence->addCompetence($competence);
                    }
                }
            }
        }

        if (count($groupeCompetence->getCompetences())<1) {
            return new JsonResponse("Les libellés des compétences sont requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($groupeCompetence);
        $em->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
}
