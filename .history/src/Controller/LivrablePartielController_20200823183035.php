<?php

namespace App\Controller;

use App\Entity\BriefPromotion;
use App\Entity\Commentaire;
use App\Entity\LivrablePartiel;
use App\Entity\LivrableRendu;
use App\Repository\ApprenantRepository;
use App\Repository\BriefRepository;
use App\Repository\FormateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StatutLivrableRepository;
use App\Repository\LivrablePartielRepository;
use App\Repository\NiveauEvaluationRepository;
use App\Repository\PromotionRepository;
use App\Repository\ReferentielRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */

class LivrablePartielController extends AbstractController
{

    /**
     * @Route("/formateurs/promo/{id_promo}/referentiel/{id_ref}/competences", name="show_competences_by_apprenant")
     */
    public function showCompetenceByApprenant(SerializerInterface $serializer,int $id_promo, int $id_ref, ReferentielRepository $repoRefrerentiel, PromotionRepository $repoPromo)
    {
        $referentiel= $repoRefrerentiel->find($id_ref);
        if (empty($referentiel)) {
            return new JsonResponse("Ce referentiel n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $promo = $repoPromo->find($id_promo);
        if (empty($promo) || !$referentiel->getPromotions()->contains($promo)) {
            return new JsonResponse("Cette promotion n'existe pas dans ce referentiel..", Response::HTTP_NOT_FOUND, [], true);
        }

        $apprenants = $promo->getApprenants();
        $apprenantsJson = $serializer->serialize($apprenants, 'json', ["groups" => ["apprenant_competence:read"]]);
        return new JsonResponse($apprenantsJson, Response::HTTP_OK, [], true);

       

    }

    /**
     * @Route("/apprenant/id/promo/id/referentiel/id/competences", name="show_competences_by_apprenant_id")
     */
    public function showCompetenceByApprenantId(SerializerInterface $serializer,, int $id_promo, int $id_ref, ReferentielRepository $repoRefrerentiel, PromotionRepository $repoPromo)
    {
        $referentiel= $repoRefrerentiel->find($id_ref);
        if (empty($referentiel)) {
            return new JsonResponse("Ce referentiel n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $promo = $repoPromo->find($id_promo);
        if (empty($promo) || !$referentiel->getPromotions()->contains($promo)) {
            return new JsonResponse("Cette promotion n'existe pas dans ce referentiel..", Response::HTTP_NOT_FOUND, [], true);
        }

        $apprenants = $promo->getApprenants();
        foreach ($apprenants as $key => $value) {
            if (condition) {
                # code...
            }
        }
        $apprenantsJson = $serializer->serialize($apprenants, 'json', ["groups" => ["apprenant_competence:read"]]);
        return new JsonResponse($apprenantsJson, Response::HTTP_OK, [], true);

       
    }

    /**
     * @Route("/apprenants/id/promo/id/referentiel/id/statistiques/briefs", name="show_statistiques_by_apprenant_id")
     */
    public function showStatistiquesByApprenantId()
    {
    }

    /**
     * @Route("/formateurs/promo/id/referentiel/id/statistiques/competences", name="show_statistiques_by_competences")
     */
    public function showStatistiquesByCompetences()
    {
    }

    /**
     * @Route("/formateurs/livrablepartiels/{id_livrable}/commentaires", name="show_commentaires_by_livrablePartiel", methods="GET")
     */
    public function showCommentairesByLivrablePartiel(SerializerInterface $serializer, int $id_livrable, LivrablePartielRepository $repoLivrablePartiels)
    {
        $livrablePartiel = $repoLivrablePartiels->find($id_livrable);
        if (empty($livrablePartiel)) {
            return new JsonResponse("Ce livrable partiel n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $commentaires = [];
        foreach ($livrablePartiel->getLivrableRendus() as $value) {
            foreach ($value->getCommentaires() as  $val) {
                $commentaires[] = $val;
            }
        }

        $commentairesJson = $serializer->serialize($commentaires, 'json', ["groups" => ["commentaire:read"]]);
        return new JsonResponse($commentairesJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/formateurs/livrablepartiels/{id_livrable}/commentaires", name="post_commentaire_by_formateur", methods="POST")
     */
    public function postCommentaireByFormateur(FormateurRepository $repoFormateur, Request $request, EntityManagerInterface $em, int $id_livrable, LivrablePartielRepository $repoLivrablePartiels)
    {

        $data = $request->request->all();

        if (!isset($data['commentaire']) || empty($data['commentaire'])) {
            return new JsonResponse("Veuillez remplir le commentaire.", Response::HTTP_BAD_REQUEST, [], true);
        }
        $commentaire = new Commentaire();
        $commentaire->setLibelle($data['commentaire']);
        $commentaire->setDate(new \DateTime());
        $file = $request->files;
        if ($file->get('pieceJointe') !== null) {
            $commentaire->setPieceJointe($this->uploadFile($file->get('pieceJointe'), "pieceJointe"));
        }


        /**Récupération du formateur connecté */

        $user = $this->getUser()->getId();
        $formateur = $repoFormateur->findBy(array('user' => $user));
        $commentaire->setFormateur($formateur[0]);

        $livrablePartiel = $repoLivrablePartiels->find($id_livrable);
        foreach ($livrablePartiel->getLivrableRendus() as $value) {
            $value->addCommentaire($commentaire);
        }
        $em->persist($commentaire);
        $em->flush();
    }

    /**
     * @Route("/apprenant/livrablepartiels/id/commentaires", name="post_commentaire_by_apprenant")
     */
    public function  postCommentaireByApprenant(ApprenantRepository $repoApprenant, Request $request, EntityManagerInterface $em, int $id, LivrablePartielRepository $repoLivrablePartiels)
    {

        $data = $request->request->all();

        if (!isset($data['commentaire']) || empty($data['commentaire'])) {
            return new JsonResponse("Veuillez remplir le commentaire.", Response::HTTP_BAD_REQUEST, [], true);
        }
        $commentaire = new Commentaire();
        $commentaire->setLibelle($data['commentaire']);
        $commentaire->setDate(new \DateTime());
        $file = $request->files;
        if ($file->get('pieceJointe') !== null) {
            $commentaire->setPieceJointe($this->uploadFile($file->get('pieceJointe'), "pieceJointe"));
        }


        /**Récupération du apprenant connecté */

        $user = $this->getUser()->getId();
        $apprenant = $repoApprenant->findBy(array('user' => $user));

        $livrablePartiel = $repoLivrablePartiels->find($id);
        foreach ($livrablePartiel->getLivrableRendus() as $value) {
            if ($value->getApprenant() == $apprenant) {
                $value->addCommentaire($commentaire);
            }
        }
        $em->persist($commentaire);
        $em->flush();
    }

    /**
     * @Route("/formateurs/promotion/{id_promo}/brief/{id_brief}/livrablepartiels", name="put_livrable_partiel_by_formateur")
     */
    public function  putLivrablePartielByFormateur(SerializerInterface $serializer, int $id_promo, int $id_brief, Request $request, EntityManagerInterface $em,StatutLivrableRepository $repoStatut,ApprenantRepository $repoApprenant , NiveauEvaluationRepository $repoNiveau, PromotionRepository $repoPromo, BriefRepository $repoBrief, LivrablePartielRepository $repoLivrablePartiels)
    {

        $data=json_decode($request->getContent(),true);
        $promo = $repoPromo->find($id_promo);
        if (empty($promo)) {
            return new JsonResponse("Ce promo n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $brief = $repoBrief->find($id_brief);
        if (empty($brief) || !$promo->getGroupes()->contains($brief)) {
            return new JsonResponse("Ce brief n'existe pas dans cette promotion..", Response::HTTP_NOT_FOUND, [], true);
        }

        /**Archivage */
        if (isset($data['id']) && $data['id']) {
            if (isset($data['deleted']) && $data['deleted']) {
                $livrablePartiel = $repoLivrablePartiels->find($data['id']);
                $livrablePartiel->setDeleted(true);
                $em->flush();
                return new JsonResponse('Groupe de Compétences archivé.', Response::HTTP_NO_CONTENT, [], true);
            }
        }

        /**Ajout de Livrable partiels */
        if (empty($data['titre'])) {
            return new JsonResponse('Le titre est requis.', Response::HTTP_BAD_REQUEST, [], true);
        }

        if (empty($data['description'])) {
            return new JsonResponse('La description est requise.', Response::HTTP_BAD_REQUEST, [], true);
        }

        if (empty($data['type'])) {
            return new JsonResponse('Le type est requis.', Response::HTTP_BAD_REQUEST, [], true);
        }

        if (empty($data['dateAffectation'])) {
            return new JsonResponse('La date d\'affectation est requise.', Response::HTTP_BAD_REQUEST, [], true);
        }

        if (empty($data['dateSoumission'])) {
            return new JsonResponse('La date de soumission est requise.', Response::HTTP_BAD_REQUEST, [], true);
        }
        if ($data['dateAffectation']>$data['dateSoumission']) {
            return new JsonResponse('La date de soumission doit etre superieure a la date d\'affectation.', Response::HTTP_BAD_REQUEST, [], true);
        }
        $niveaux = $data['niveauCompetences'];
        if (count($niveaux)<0) {
            return new JsonResponse('Un niveau est requis.', Response::HTTP_BAD_REQUEST, [], true);
        }

        $apprenants = $data['apprenants'];
        if (count($apprenants)<0) {
            return new JsonResponse('Veuillez affecter ce livrable à des apprenants.', Response::HTTP_BAD_REQUEST, [], true);
        }

        $livrablePartiel =  $serializer->denormalize($data, LivrablePartiel::class, true, ["groups" => "livrable:write"]);
        
        // Traitement prom et brief
        $briefPromotion = new BriefPromotion();
        $briefPromotion->setPromotion($promo);
        $briefPromotion->setBrief($brief);

        // Traitement niveaux
        foreach ($niveaux as  $value) {
            $niveauEvaluation = $repoNiveau->find($value);
            if ($niveauEvaluation) {
                $livrablePartiel->addNiveauCompetence($niveauEvaluation);
            }
        }

        //Traitement des affectations
        foreach ($apprenants as  $value) {
            $apprenant = $repoApprenant->find($value);
            if ($apprenant) {
                $livrableRendu = new LivrableRendu();
                $livrableRendu->setLivrablePartiel($livrablePartiel);
                $livrableRendu->setApprenant($apprenant);
                $livrableRendu->setStatut($repoStatut->findBy(array('libelle' => 'ASSIGNE'))[0]);
            }
        }
        //$em->persist($livrablePartiel);
        //$em->flush();

    }

    /**
     * @Route("/apprenants/{id_apprenant}/livrablepartiels/{id_livrable}", name="put_statut_by_apprenant")
     */
    public function  putStatutByApprenant(Request $request, EntityManagerInterface $em, int $id_apprenant, int $id_livrable, StatutLivrableRepository $repoStatut, ApprenantRepository $repoApprenant, LivrablePartielRepository $repoLivrablePartiels)
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['statut']) || empty($data['statut'])) {
            return new JsonResponse("Veuillez remplir le statut.", Response::HTTP_BAD_REQUEST, [], true);
        }
        $livrablePartiel = $repoLivrablePartiels->find($id_livrable);
        $apprenant = $repoApprenant->find($id_apprenant);
        $statut = $repoStatut->findBy(array('libelle' => $data['statut']));

        if (empty($statut)) {
            return new JsonResponse("Cet statut n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        foreach ($livrablePartiel->getLivrableRendus() as $value) {
            if ($apprenant == $value->getApprenant()) {
                $value->setStatut($statut[0]);
                //$em->persist($value);
            }
        }

        //$em->flush();
    }




    /**Fonction traitement image */
    public function uploadFile($file, $name)
    {
        $fileType = explode("/", $file->getMimeType())[1];
        $filePath = $file->getRealPath();
        return file_get_contents($filePath, $name . '.' . $fileType);
    }
}
