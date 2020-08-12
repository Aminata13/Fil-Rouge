<?php

namespace App\Controller;

use App\Entity\Referentiel;
use App\Repository\ReferentielRepository;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ShowGroupeByReferentielController extends AbstractController
{
    private $repoReferentiel;
    private $repoGroupeComp;
    private $serializer;

    public function __construct(ReferentielRepository $repoReferentiel, GroupeCompetenceRepository $repoGroupeComp, SerializerInterface $serializer)
    {
        $this->repoReferentiel = $repoReferentiel;
        $this->repoGroupeComp = $repoGroupeComp;
        $this->serializer = $serializer;
    }

    /**
     * @Route(
     *     name="show_groupe_referentiel_id",
     *     path="/api/admin/referentiels/{id_referentiel}/groupe_competences/{id_groupe}",
     *     methods={"GET"}
     * )
     */
    public function showGroupe(int $id_referentiel, int $id_groupe)
    {
        $referentiel = $this->repoReferentiel->find($id_referentiel);
        if(is_null($referentiel)) {
            return new JsonResponse("Ce référentiel n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $groupeCompetence = $this->repoGroupeComp->find($id_groupe);
        if(is_null($groupeCompetence)) {
            return new JsonResponse("Ce groupe de compétences n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
        }

        foreach ($referentiel->getGroupeCompetences() as  $value) {
            if ($value != $groupeCompetence) {
                $referentiel->removeGroupeCompetence($value);
            }
        }
        if (count($referentiel->getGroupeCompetences()) < 1) {
            return new JsonResponse("Ce groupe de compétence n'est pas lié à ce référentiel.", Response::HTTP_BAD_REQUEST, [], true);
        }


        $referentielJson = $this->serializer->serialize($referentiel, 'json',["groups"=>["referentiel:read_all"]]);
        return new JsonResponse($referentielJson, Response::HTTP_OK, [], true);
    }
}
