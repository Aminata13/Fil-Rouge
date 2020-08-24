<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Repository\GroupeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ShowApprenantsByGroupe
{
    private $repoGroupe;
    private $serializer;

    public function __construct(GroupeRepository $repoGroupe, SerializerInterface $serializer)
    {
        $this->repoGroupe = $repoGroupe;
        $this->serializer = $serializer;
    }

    /**
     * @Route(
     *     name="show_apprenants_groupe",
     *     path="/api/admin/groupes/apprenants",
     *     methods={"GET"},
     *     defaults={
     *         "_api_resource_class"=Groupe::class,
     *         "_api_collection_operation_name"="get_apprenants_groupe"
     *     }
     * )
     */
    public function __invoke()
    {
        $groupes = $this->repoGroupe->findAll();

        $groupesJson = $this->serializer->serialize($groupes, 'json',["groups"=>["apprenants_groupe:read"]]);
        return new JsonResponse($groupesJson, Response::HTTP_OK, [], true);


        $apprenantsJson = $this->serializer->serialize($apprenants, 'json',["groups"=>["profil"]]);

    }
}
