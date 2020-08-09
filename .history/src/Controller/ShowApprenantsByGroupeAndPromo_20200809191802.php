<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Repository\PromotionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ShowApprenantsByGroupeAndPromo
{
    private $repo;
    private $serializer;

    public function __construct(PromotionRepository $repo, SerializerInterface $serializer)
    {
        $this->repo = $repo;
        $this->serializer = $serializer;
    }

    /**
     * @Route(
     *     name="show_apprenants_id_attente",
     *     path="/api/admin/promotion/{id}/apprenants/attente",
     *     methods={"GET"},
     *     defaults={
     *         "_api_resource_class"=Promotion::class,
     *         "_api_collection_operation_name"="get_apprenants_id_attente"
     *     }
     * )
     */
    public function __invoke(Promotion $data)
    {

        foreach ($data->getApprenants() as  $apprenant) {
            if (!$apprenant->getAttente()) {
                $data->removeApprenant($apprenant);
            }
        }

        $promoJson = $this->serializer->serialize($data, 'json');
        return new JsonResponse($promoJson, Response::HTTP_OK, [], true);
    }
}
