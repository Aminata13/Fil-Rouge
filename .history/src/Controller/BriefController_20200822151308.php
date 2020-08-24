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
    public function getBriefByPromoId(SerializerInterface $serializer, int $id_promo, PromotionRepository $repoPromo, BriefRepository $repoBrief, GroupeRepository $ripoGroupe){
        $promo = $repoPromo->find($id_promo);
        if (empty($promo)) {
            return new JsonResponse("Ce promo n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $promoJson = $serializer->serialize($promo, 'json', ["groups" => ["briefGroupe:read"]]);
        return new JsonResponse($promoJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/formateurs/{id}/briefs/brouillons", name="show_brief_brouillons_formateur", methods="GET")
     */
    public function getBriefBroullonFormateurId(SerializerInterface $serializer, int $id, int $id_groupe,PromotionRepository $repoPromo, BriefPromotionRepository $repoBriefPromo, FormateurRepository $repoFormateur, BriefRepository $repoBrief, GroupeRepository $ripoGroupe){
        
        $formateur = $repoFormateur->find($id);
        if (empty($formateur)) {
            return new JsonResponse("Ce formateur n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        foreach ($formateur->getBriefs() as $value){
            if ($value->getEtatBrief()->getLibelle()!="BROUILLON"){
                $formateur->removeBrief($value);
            }
        }

        if (empty($formateur->getBriefs())) {
            return new JsonResponse("Aucun brief en brouillon.", Response::HTTP_NOT_FOUND, [], true);
        }

        $briefJson = $serializer->serialize($formateur->getBriefs(), 'json', ["groups" => ["briefGroupe:read"]]);
        return new JsonResponse($briefJson, Response::HTTP_OK, [], true);
    }

        /**
     * @Route("/formateurs/{id}/briefs/valide", name="show_brief_valide_formateur", methods="GET")
     */
    public function getBriefValideFormateurId(SerializerInterface $serializer, int $id, int $id_groupe,PromotionRepository $repoPromo, BriefPromotionRepository $repoBriefPromo, FormateurRepository $repoFormateur, BriefRepository $repoBrief, GroupeRepository $ripoGroupe){
        
        $formateur = $repoFormateur->find($id);
        if (empty($formateur)) {
            return new JsonResponse("Ce formateur n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        foreach ($formateur->getBriefs() as $value){
            if ($value->getEtatBrief()->getLibelle()!="COMPLET"){
                $formateur->removeBrief($value);
            }
        }

        if (empty($formateur->getBriefs())) {
            return new JsonResponse("Aucun brief valide.", Response::HTTP_NOT_FOUND, [], true);
        }

        $briefJson = $serializer->serialize($formateur->getBriefs(), 'json', ["groups" => ["briefGroupe:read"]]);
        return new JsonResponse($briefJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/formateurs/promotions/{id_promo}/briefs/{id_brief}", name="show_promo_id_brief_id", methods="GET")
     */
    public function getBriefByPromo(SerializerInterface $serializer, int $id_promo, int $id_brief,PromotionRepository $repoPromo, BriefRepository $repoBrief){
    
        $promo = $repoPromo->find($id_promo);
        if (empty($promo)) {
            return new JsonResponse("Ce promo n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $brief = $repoBrief->find($id_brief);
        if (empty($brief) || !$promo->getGroupes()->contains($brief)) {
            return new JsonResponse("Ce groupe n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $briefJson = $serializer->serialize($brief, 'json', ["groups" => ["briefGroupe:read"]]);
        return new JsonResponse($briefJson, Response::HTTP_OK, [], true);
    }
}