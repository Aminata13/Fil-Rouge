<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class AddGroupeCompetence
{
    private $repo;
    private $serializer;

    public function __construct(UserRepository $repo, SerializerInterface $serializer)
    {
        $this->repo = $repo;
        $this->serializer = $serializer;
    }

    /**
     * @Route(
     *     name="apprenant_liste",
     *     path="/api/eleves",
     *     methods={"GET"},
     *     defaults={
     *         "_api_resource_class"=User::class,
     *         "_api_collection_operation_name"="show_apprenants"
     *     }
     * )
     */
    public function __invoke()
    {
        $apprenants = $this->repo->findByProfil("APPRENANT");
        $apprenantsJson = $this->serializer->serialize($apprenants, 'json');
        return new JsonResponse($apprenantsJson, Response::HTTP_OK, [], true);
    }
}
