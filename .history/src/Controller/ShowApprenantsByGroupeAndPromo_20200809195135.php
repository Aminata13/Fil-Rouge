<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Repository\GroupeRepository;
use App\Repository\PromotionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ShowApprenantsByGroupeAndPromo
{
    private $repoPromo;
    private $repoGroupe;
    private $serializer;

    public function __construct(PromotionRepository $repoPromo, GroupeRepository $repoGroupe, SerializerInterface $serializer)
    {
        $this->repoPromo = $repoPromo;
        $this->repoGroupe = $repoGroupe;
        $this->serializer = $serializer;
    }

    /**
     * @Route(
     *     name="show_promo_id_groupes_id_apprenants",
     *     path="/api/admin/promotion/{id_promo}/groupes/{id_groupe}/apprenants",
     *     methods={"GET"},
     *     defaults={
     *         "_api_resource_class"=Promotion::class,
     *         "_api_collection_operation_name"="get_promo_id_groupes_id_apprenants"
     *     }
     * )
     */
    public function __invoke(int $id_promo, int $id_groupe)
    {
        $promo = $this->repoPromo->find($id_promo);
        $groupe = $this->repoGroupe->find($id_groupe);

        foreach ($promo->getGroupes() as  $value) {
            if ($value != $groupe) {
                $promo->removeGroupe($value)
            }
        }


        $promoJson = $this->serializer->serialize($data, 'json');
        return new JsonResponse($promoJson, Response::HTTP_OK, [], true);
    }
}
