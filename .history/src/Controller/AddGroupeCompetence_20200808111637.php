<?php

namespace App\Controller;

use App\Entity\GroupeCompetence;
use App\Repository\CompetenceRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AddGroupeCompetence
{
    private $em;
    private $repo;
    private $serializer;

    public function __construct(CompetenceRepository $repo, SerializerInterface $serializer, Enti)
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
