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
    public function addGroupe(Groupe $data)
    {
        $errors = $this->validator->validate($data);
        if (($errors) > 0) {
            $errorsString = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }

        // $tags = $data->getTags();
        
        // if (count($tags) < 1) {
        //     return new JsonResponse("Un tag est requis.", Response::HTTP_BAD_REQUEST, [], true);
        // }

        // $groupeTag = new GroupeTag();
        // $groupeTag->setLibelle($data->getLibelle());
        // $tabLibelle = [];


        // foreach ($tags as $value) {
        //     if (!empty($value->getLibelle())) {
        //         $tag = $this->repo->findBy(array('libelle' => $value->getLibelle()));
        //         if ($tag) {
        //             $groupeTag->addTag($tag[0]);
        //         } else {
        //             if (!in_array($value->getlibelle(), $tabLibelle)) {
        //                 $tabLibelle[] = $value->getlibelle();
        //                 $tag = new Tag();
        //                 $tag->setLibelle($value->getLibelle());
        //                 $groupeTag->addTag($tag);
        //             }
        //         }
        //     }
        // }

        // if (count($groupeTag->getTags())<1) {
        //     return new JsonResponse("Le libelle d'un tag est requis.", Response::HTTP_BAD_REQUEST, [], true);
        // }

        // $this->em->persist($groupeTag);
        // $this->em->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
}
