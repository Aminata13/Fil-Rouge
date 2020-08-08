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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class AddGroupeCompetence
{
    private $em;
    private $repo;
    private $serializer;
    private $validator;


    public function __construct(CompetenceRepository $repo, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->repo = $repo;
        $this->serializer = $serializer;
        $this->validator = $validator;
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
        $errors = $this->validator->validate($data);
        if (($errors) > 0) {
            $errorsString = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }

        $competences = $data->getCompetences();
        if (count($competences)<1) {
            return new JsonResponse("Une compétence est requise.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $groupeCompetence = new GroupeCompetence();
        $groupeCompetence->setLibelle($data->getLibelle());
        $groupeCompetence->setDescription($data->getDescription());
        $tabLibelle = [];
        

        foreach ($competences as $value) {
            if (empty($value->getLibelle())) {
                return new JsonResponse("Le libelle d'une compétence est requis.", Response::HTTP_BAD_REQUEST, [], true);
            }
            $competence = $this->repo->findBy(array('libelle' => $value->getLibelle()));
            if ($competence) {
                $groupeCompetence->addCompetence($competence[0]);
            } else {
                if (!in_array($value->getlibelle(), $tabLibelle)) {
                    $tabLibelle[] = $value->getlibelle();
                    $competence = new Competence();
                    $competence->setLibelle($value->getLibelle());
                    $groupeCompetence->addCompetence($competence);
                }
            }
        }

        $this->em->persist($groupeCompetence);
        $this->em->flush();
        return new JsonResponse("succes", Response::HTTP_CREATED, [], true);
    }
}
