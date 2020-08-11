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
        $groupeCompetence = $this->repoGroupeComp->find($id_groupe);

        foreach ($referentiel->getGroupeCompetences() as  $value) {
            if ($value != $groupeCompetence) {
                $referentiel->removeGroupeCompetence($value);
            }
        }

        $referentielJson = $this->serializer->serialize($referentiel, 'json',["groups"=>["referentiel:read_all"]]);
        return new JsonResponse($referentielJson, Response::HTTP_OK, [], true);
    }
}
