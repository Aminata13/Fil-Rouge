<?php 

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/api")
 */
class BriefController extends AbstractController
{

    /**
     * @Route("/formateurs/promotions/{id_promo}/gr/{id_apprenant}/chats", name="show_messages_apprenant", methods="GET")
     */
    public function getBriefByPromoIdByGroupeId(){

    }

}