<?php 

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/api")
 */
class BriefController extends AbstractController
{

    /**
     * @Route("/formateurs/promotions/{id_promo}/groupe/{id_groupe}/briefs", name="Brief_by_promoId_byGroupeId", methods="GET")
     */
    public function getBriefByPromoIdByGroupeId(){

    }

}