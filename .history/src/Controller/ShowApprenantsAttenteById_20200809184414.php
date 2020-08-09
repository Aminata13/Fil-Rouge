<?php

namespace App\Controller;

use App\Entity\User;
use App\Controller\UserController;
use App\Entity\Promotion;
use App\Repository\ApprenantRepository;
use App\Repository\PromotionRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ShowApprenantsAttenteById 
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
        $promos = $this->repo->findAll();
        
        foreach ($promos as $value) {
            foreach ($value->getApprenants() as  $apprenant) {
                if (!$apprenant->getAttente()) {
                    $value->removeApprenant($apprenant);
                }
            }
        }

        $promosJson = $this->serializer->serialize($promos, 'json');
        return new JsonResponse($promosJson, Response::HTTP_OK, [], true);
    }
}
