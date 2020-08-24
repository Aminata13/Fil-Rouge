<?php

use App\Repository\BriefRepository;
use App\Repository\GroupeRepository;
use App\Repository\PromotionRepository;
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
    public function getBriefByPromoIdByGroupeId(int $id_promo, int $id_groupe,PromotionRepository $repoPromo, BriefRepository $repoBrief, GroupeRepository $ripoGroupe){
    
        $promo = $repoPromo->find($id_promo);
        $groupe = $ripoGroupe->find($id_groupe);

        if ($promo->getGroupes()) {
            # code...
        }

    }

}