<?php

namespace App\Controller;

use App\Repository\BriefPromotionRepository;
use App\Repository\BriefRepository;
use App\Repository\FormateurRepository;
use App\Repository\GroupeRepository;
use App\Repository\PromotionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/api")
 */
class BriefController extends AbstractController
{

    /**
     * @Route("/formateurs/promotions/{id_promo}/groupe/{id_groupe}/briefs", name="show_brief_by_promoId_by_groupe_id", methods="GET")
     */
    public function getBriefByPromoIdByGroupeId(SerializerInterface $serializer, int $id_promo, int $id_groupe,PromotionRepository $repoPromo, BriefRepository $repoBrief, GroupeRepository $ripoGroupe){
    
        $promo = $repoPromo->find($id_promo);
        if (empty($promo)) {
            return new JsonResponse("Ce promo n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $groupe = $ripoGroupe->find($id_groupe);
        if (empty($groupe) || !$promo->getGroupes()->contains($groupe)) {
            return new JsonResponse("Ce groupe n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $briefJson = $serializer->serialize($groupe, 'json', ["groups" => ["briefGroupe:read"]]);
        return new JsonResponse($briefJson, Response::HTTP_OK, [], true);
    }


    /**
     * @Route("/formateurs/promotions/{id_promo}/briefs", name="show_brief_by_promoId", methods="GET")
     */
    public function getBriefByPromoId(SerializerInterface $serializer, int $id_promo, int $id_groupe,PromotionRepository $repoPromo, BriefRepository $repoBrief, GroupeRepository $ripoGroupe){
        $promo = $repoPromo->find($id_promo);
        if (empty($promo)) {
            return new JsonResponse("Ce promo n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $groupe = $ripoGroupe->find($id_groupe);
        if (empty($groupe) || !$promo->getGroupes()->contains($groupe)) {
            return new JsonResponse("Ce groupe n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $briefJson = $serializer->serialize($groupe, 'json', ["groups" => ["briefGroupe:read"]]);
        return new JsonResponse($briefJson, Response::HTTP_OK, [], true);
    }

        /**
     * @Route("/formateurs/{id}/briefs/broullons", name="show_brief_broullons_formateur", methods="GET")
     */
    public function getBriefBroullonFormateurId(SerializerInterface $serializer, int $id, int $id_groupe, BriefPromotionRepository $repoBriefPromo, FormateurRepository $repoFormateur, BriefRepository $repoBrief, GroupeRepository $ripoGroupe){
        
        $etatBriefPromoCourent = $repoBriefPromo->findBy(array('date' => 'desc'),1,)
        $formateur = $repoFormateur->find($id);
        if (empty($formateur)) {
            return new JsonResponse("Ce formateur n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }
        


        foreach ($formateur->getBriefs() as $value){
            if ($value->getEtatBriefGroupe()->getStatut()->getLibelle()=="BROULLON") {
            }
        }

        $briefJson = $serializer->serialize($groupe, 'json', ["groups" => ["briefGroupe:read"]]);
        return new JsonResponse($briefJson, Response::HTTP_OK, [], true);
    }

}