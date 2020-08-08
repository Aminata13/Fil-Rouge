<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\NiveauEvaluation;
use App\Repository\GroupeTagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class AddTag
{
    private $em;
    private $repo;
    private $serializer;
    private $validator;


    public function __construct(GroupeTagRepository $repo, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->repo = $repo;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @Route(
     *     name="add_tag",
     *     path="/api/admin/tags",
     *     methods={"POST"},
     *     defaults={
     *         "_api_resource_class"=Tag::class,
     *         "_api_collection_operation_name"="post_tag"
     *     }
     * )
     */
    public function __invoke(Tag $data)
    {
        $errors = $this->validator->validate($data);
        if (($errors) > 0) {
            $errorsString = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }
        $groupeTag = $data->getGroupeTags()[0]->getLibelle();
        if (empty($groupeTag)) {
            return new JsonResponse("Veuillez rattacher le tag à un groupe de tags.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $tag = new Tag();
        $grpTag =$this->repo->findBy(array('libelle' => $groupeCompetence));
        
        if (empty($grpComp)) {
            return new JsonResponse("Ce groupe de competence n\'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
        }
        $competence->addGroupeCompetence($grpComp[0]);
        $competence->setLibelle($data->getLibelle());
        $tabLibelle = [];

        foreach ($niveaux as $value) {
            if (!empty($value->getLibelle()) && !empty($value->getGroupeAction()) && !empty($value->getCritereEvaluation())) {
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

        if (count($competence->getNiveaux()) != 3) {
            return new JsonResponse("Une compétence requiert trois niveaux avec leur groupe d'action et leur critere d'evaluation.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $this->em->persist($competence);
        $this->em->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
}
