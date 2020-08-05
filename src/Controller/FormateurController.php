<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class FormateurController 
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
     *     name="formateur_liste",
     *     path="/api/formateurs",
     *     methods={"GET"},
     *     defaults={
     *         "_api_resource_class"=User::class,
     *         "_api_collection_operation_name"="show_formateurs"
     *     }
     * )
     */
    public function __invoke()
    {
        $formateurs = $this->repo->findByProfil("FORMATEUR");
        $formateursJson = $this->serializer->serialize($formateurs, 'json');
        return new JsonResponse($formateursJson, Response::HTTP_OK, [], true);
    }
}
