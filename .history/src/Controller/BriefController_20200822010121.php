<?php 

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/api")
 */
class BriefController extends AbstractController
{

    /**
     * @Route("/users/promotions/{id_promo}/apprenants/{id_apprenant}/chats", name="show_messages_apprenant", methods="GET")
     */
    public function getBriefByPromoIdByGroupeId()

}