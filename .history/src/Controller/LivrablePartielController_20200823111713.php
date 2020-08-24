<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/formateurs/livrablepartiels/id/commentaires", name="show_commentaires_by_livrablePartiel", methods="GET")
     */
    public function showCommentairesByLivrablePartiel()
    {
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
    public function  putlivrablepartielbyformateur()
    {
    }
}
