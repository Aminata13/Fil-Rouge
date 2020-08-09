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

    public function __construct(UserRepository $repo, SerializerInterface $serializer, UserController $userController)
    {
        $this->repo = $repo;
        $this->serializer = $serializer;
        $this->user = $userController;
    }

     /**
     * @Route(
     *     name="show_apprenants_attente",
     *     path="/api/admin/appren",
     *     methods={"POST"},
     *     defaults={
     *         "_api_resource_class"=Competence::class,
     *         "_api_collection_operation_name"="post_competence"
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
