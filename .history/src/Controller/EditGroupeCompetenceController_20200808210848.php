<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Entity\GroupeCompetence;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditGroupeCompetenceController extends AbstractController
{
    /**
     * @Route(
     *     name="edit_groupe_competence",
     *     path="/api/admin/groupe_competences/{id}",
     *     methods={"PUT"}
     * )
     */
    public function editGroupeCompetence(CompetenceRepository $repo, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator,Request $request)
    {
        dd($request->get);
        $errors = $validator->validate($data);
        if (($errors) > 0) {
            $errorsString = $serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }

        $competences = $data->getCompetences();
        if (count($competences) < 1) {
            return new JsonResponse("Une compétence est requise.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $groupeCompetence = new GroupeCompetence();
        $groupeCompetence->setLibelle($data->getLibelle());
        $groupeCompetence->setDescription($data->getDescription());
        $tabLibelle = [];


        foreach ($competences as $value) {
            if (!empty($value->getLibelle())) {
                $competence = $repo->findBy(array('libelle' => $value->getLibelle()));
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
        }

        if (count($groupeCompetence->getCompetences())<1) {
            return new JsonResponse("Une compétence est requise.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($groupeCompetence);
        $em->flush();
        return new JsonResponse("succes", Response::HTTP_CREATED, [], true);
    }
}
