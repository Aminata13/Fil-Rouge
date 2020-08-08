<?php

namespace App\Controller;

use App\Entity\GroupeCompetence;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Competence;
use App\Entity\NiveauEvaluation;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class AddCompetence
{
    private $em;
    private $repo;
    private $serializer;
    private $validator;


    public function __construct(GroupeCompetenceRepository $repo, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->repo = $repo;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @Route(
     *     name="add_competence",
     *     path="/api/admin/competences",
     *     methods={"POST"},
     *     defaults={
     *         "_api_resource_class"=Competence::class,
     *         "_api_collection_operation_name"="post_competence"
     *     }
     * )
     */
    public function __invoke(Competence $data)
    {
        $errors = $this->validator->validate($data);
        if (($errors) > 0) {
            $errorsString = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }
        $groupeCompetence = $data->getGroupeCompetences()[0]->getLibelle();
        if (empty($groupeCompetence)) {
            return new JsonResponse("Une compétence requiert trois niveaux.", Response::HTTP_BAD_REQUEST, [], true);
        }
        dd($groupeCompetence[0]->getLibelle());
        $niveaux = $data->getNiveaux();
        if (count($niveaux) < 3) {
            return new JsonResponse("Une compétence requiert trois niveaux.", Response::HTTP_BAD_REQUEST, [], true);
        }

        
        $competence = new Competence();
        $competence->addGroupeCompetence();
        $competence->setLibelle($data->getLibelle());

        $tabLibelle = [];


        foreach ($niveaux as $value) {
            if (!empty($value->getLibelle()) || !empty($value->getGroupeAction()) || !empty($value->getCritereEvaluation())) {
                    if (!in_array($value->getlibelle(), $tabLibelle)) {
                        $tabLibelle[] = $value->getlibelle();
                        $niveau = new NiveauEvaluation();

                        $niveau->setLibelle($value->getLibelle());
                        $niveau->setGroupeAction($value->getGroupeAction());
                        $niveau->setCritereEvaluation($value->getCritereEvaluation());

                        $competence->addNiveau($niveau);
                    }
            }
        }

        if (count($competence->getNiveaux())< 3) {
            return new JsonResponse("Une compétence requiert trois niveaux.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $this->em->persist($competence);
        $this->em->flush();
        return new JsonResponse("succes", Response::HTTP_CREATED, [], true);
    }
}
