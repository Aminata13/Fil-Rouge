<?php

namespace App\Controller;

use App\Entity\User;
use App\Controller\UserController;
use App\Repository\ApprenantRepository;
use App\Repository\PromotionRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ShowApprenantsAttente 
{
    private $repo;

    public function __construct(PromotionRepository $repo)
    {
        $this->repo = $repo;
    }

     /**
     * @Route(
     *     name="show_apprenants_attente",
     *     path="/api/admin/promotion/apprenants/attente",
     *     methods={"GET"},
     *     defaults={
     *         "_api_resource_class"=Promotion::class,
     *         "_api_collection_operation_name"="get_apprenants_attente"
     *     }
     * )
     */
    public function __invoke()
    {
        
        // $apprenants = $this->repo->findByProfil("APPRENANT");
        // $apprenantsJson = $this->serializer->serialize($apprenants, 'json');
        // return new JsonResponse($apprenantsJson, Response::HTTP_OK, [], true);
    }
}
