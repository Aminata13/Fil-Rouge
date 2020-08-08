<?php

namespace App\Controller;

use App\Entity\GroupeCompetence;
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
     *     name="add_groupe_competence",
     *     path="/api/admin/groupe_competences",
     *     methods={"POST"},
     *     defaults={
     *         "_api_resource_class"=GroupeCompetence::class,
     *         "_api_collection_operation_name"="post_groupe_competence"
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
