<?php

namespace App\Controller;

use App\Entity\User;
use App\Controller\UserController;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ShowApprenantsAttente 
{
    private $repo;
    private $serializer;
    private $user;

    public function __construct(App $repo, SerializerInterface $serializer, UserController $userController)
    {
        $this->repo = $repo;
        $this->serializer = $serializer;
        $this->user = $userController;
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
