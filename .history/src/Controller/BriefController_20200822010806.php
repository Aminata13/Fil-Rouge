<?php 

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/api")
 */
class BriefController extends AbstractController
{

    /**
     * @Route("/formateurs/promotions/{id_promo}/groupe/{id_groupe}/briefs", name="show_brief_by_promoId_by_groupe_id", methods="GET")
     */
    public function getBriefByPromoIdByGroupeId(int $id_promo, int $id_groupe){
        

    }

}