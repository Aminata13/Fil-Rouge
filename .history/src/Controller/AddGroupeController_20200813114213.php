<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Repository\GroupeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class AddGroupeController 
{
    private $em;
    private $repo;
    private $validator;


    public function __construct(GroupeRepository $repo, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->repo = $repo;
        $this->validator = $validator;
    }

    /**
     * @Route(
     *     name="add_groupe",
     *     path="/api/admin/groupes",
     *     methods={"POST"},
     * )
     */
    public function addGroupe()
    {
        $errors = $this->validator->validate($data);
        if (($errors) > 0) {
            $errorsString = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }

        

        // $this->em->persist();
        // $this->em->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
}
