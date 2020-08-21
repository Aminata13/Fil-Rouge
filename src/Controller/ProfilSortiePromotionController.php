<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Repository\PromotionRepository;
use App\Repository\ProfilSortieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("/api")
*/
class ProfilSortiePromotionController extends AbstractController
{
    /**
     * @Route("/admin/promotion/{id_promo}/profil_sortie/{id_profil}", name="profil_sortie_promotion")
     */
    public function index(int $id_promo, int $id_profil, PromotionRepository $repoPromo,ProfilSortieRepository $repoProfil)
    {
        $promo=$repoPromo->find($id_promo);
        if(is_null($promo)) {
            return new JsonResponse("Cette promotion n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }
        $profilSortie=$repoProfil->find($id_profil);
        if(is_null($profilSortie)) {
            return new JsonResponse("Ce profil de sortie n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }
        $apprenants=array();
        foreach($promo->getApprenants() as $apprenant){
            if($apprenant->getProfilSortie()==$profilSortie){ 
                $apprenants[]=$apprenant;
                
            }

        }
        if(empty($apprenants)) {
            return new JsonResponse("Il n'y a aucun apprenant avec ce profil de sortie.", Response::HTTP_NOT_FOUND, [], true);
        }
        $apprenantsJson = $this->serializer->serialize($apprenants, 'json',["groups"=>["profil_sortie_promo:read"]]);
        return new JsonResponse($apprenantsJson, Response::HTTP_OK, [], true);
    }
}
