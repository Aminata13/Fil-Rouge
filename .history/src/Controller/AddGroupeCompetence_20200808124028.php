<?php

namespace App\Controller;

use App\Entity\GroupeCompetence;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class AddGroupeCompetence
{
    private $em;
    private $repo;
    private $serializer;
    

    public function __construct(CompetenceRepository $repo, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $this->em = $em;
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
    public function __invoke(GroupeCompetence $data)
    {
        //dd($data);
        $em
        return new JsonResponse("succes", Response::HTTP_CREATED, [], true);
    }
}
