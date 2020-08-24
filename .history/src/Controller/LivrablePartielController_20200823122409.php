<?php

namespace App\Controller;

use App\Repository\ApprenantRepository;
use App\Repository\LivrablePartielRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LivrablePartielController extends AbstractController
{
    /**
     * @Route("/formateurs/promo/id/referentiel/id/competences", name="show_competences_by_apprenant")
     */
    public function showCompetenceByApprenant()
    {
    }

    /**
     * @Route("/apprenant/id/promo/id/referentiel/id/competences", name="show_competences_by_apprenant_id")
     */
    public function showCompetenceByApprenantId()
    {
    }

    /**
     * @Route("/apprenants/id/promo/id/referentiel/id/statistiques/briefs", name="show_statistiques_by_apprenant_id")
     */
    public function showStatistiquesByApprenantId()
    {
    }

    /**
     * @Route("/formateurs/promo/id/referentiel/id/statistiques/competences", name="show_statistiques_by_competences")
     */
    public function showStatistiquesByCompetences()
    {
    }

    /**
     * @Route("/formateurs/livrablepartiels/{id}/commentaires", name="show_commentaires_by_livrablePartiel", methods="GET")
     */
    public function showCommentairesByLivrablePartiel(int $id, LivrablePartielRepository $repoLivrablePartiels)
    {
        $livrablePartiel = $repoLivrablePartiels->find($id);
        if (empty($livrablePartiel)) {
            return new JsonResponse("Ce livrable partiel n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }
        
        $commentaires = [];
        foreach ($livrablePartiel->getLivrableRendus() as $value) {
            foreach ($value->getCommentaires() as  $val) {
                $commentaires[] = $val;
            }
        }
        
    }

    /**
     * @Route("/formateurs/livrablepartiels/id/commentaires", name="post_commentaire_by_formateur", methods="POST")
     */
    public function postCommentaireByFormateur()
    {
    }

    /**
     * @Route("/apprenant/livrablepartiels/id/commentaires", name="post_commentaire_by_apprenant")
     */
    public function  postCommentaireByApprenant()
    {
    }
    
    /**
     * @Route("/formateurs/promo/id/brief/id/livrablepartiels", name="put_livrable_partiel_by_formateur")
     */
    public function  putLivrablePartielByFormateur()
    {
    }

    /**
     * @Route("/apprenants/{id_apprenant}/livrablepartiels/{id_livrable}", name="put_statut_by_apprenant")
     */
    public function  putStatutByApprenant(int $id_apprenant, int $id_livrable, ApprenantRepository $repoApprenant, LivrablePartielRepository)
    {

    }
}
