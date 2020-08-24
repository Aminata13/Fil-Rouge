<?php

namespace App\Controller;

use App\Entity\BriefPromotion;
use App\Entity\Commentaire;
use App\Entity\LivrablePartiel;
use App\Entity\LivrableRendu;
use App\Repository\ApprenantRepository;
use App\Repository\BriefApprenantRepository;
use App\Repository\BriefRepository;
use App\Repository\CompetenceValideRepository;
use App\Repository\FormateurRepository;
use App\Repository\GroupeRepository;
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
    public function showCompetenceByApprenant(SerializerInterface $serializer, int $id_promo, int $id_ref, ReferentielRepository $repoRefrerentiel, PromotionRepository $repoPromo)
    {
        $referentiel = $repoRefrerentiel->find($id_ref);
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
     * @Route("/apprenant/{id_apprenant}/promo/{id_promo}/referentiel/{id_ref}/competences", name="show_competences_by_apprenant_id")
     */
    public function showCompetenceByApprenantId(SerializerInterface $serializer, int $id_apprenant, int $id_promo, int $id_ref, ApprenantRepository $repoApprenant, ReferentielRepository $repoRefrerentiel, PromotionRepository $repoPromo)
    {
        $referentiel = $repoRefrerentiel->find($id_ref);
        if (empty($referentiel)) {
            return new JsonResponse("Ce referentiel n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $promo = $repoPromo->find($id_promo);
        if (empty($promo) || !$referentiel->getPromotions()->contains($promo)) {
            return new JsonResponse("Cette promotion n'existe pas dans ce referentiel..", Response::HTTP_NOT_FOUND, [], true);
        }

        $apprenant = $repoApprenant->find($id_apprenant);
        if (empty($apprenant) || !$promo->getApprenants()->contains($apprenant)) {
            return new JsonResponse("Cet apprenant n'existe pas dans cette promotion..", Response::HTTP_NOT_FOUND, [], true);
        }

        $apprenantJson = $serializer->serialize($apprenant, 'json', ["groups" => ["apprenant_competence:read"]]);
        return new JsonResponse($apprenantJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/apprenants/{id_apprenant}/promo/{id_promo}/referentiel/{id_ref}/statistiques/briefs", name="show_statistiques_by_apprenant_id")
     */
    public function showStatistiquesByApprenantId(SerializerInterface $serializer, int $id_apprenant, int $id_promo, int $id_ref, ApprenantRepository $repoApprenant, BriefApprenantRepository $repoBriefApprenant, ReferentielRepository $repoReferentiel)
    {
        $apprenant = $repoApprenant->findOneBySomeField($id_apprenant, $id_promo);
        if (!$apprenant) {
            return new JsonResponse("Cet apprenant n'existe pas dans cette promotion.", Response::HTTP_NOT_FOUND, [], true);
        }

        $referentiel = $repoReferentiel->find($id_ref);
        $promo = $apprenant->getPromotion();
        if (!$promo->getReferentiels()->contains($referentiel)) {
            return new JsonResponse("Ce referentiel partiel n'existe pas dans cette promotion.", Response::HTTP_NOT_FOUND, [], true);
        }
        $briefs = $referentiel->getBriefs();
        $briefPromotions = [];
        foreach ($promo->getBriefPromotions() as  $value) {
            if ($briefs->contains($value->getBrief())) {
                $briefPromotions[] = $value;
            }
        }
        $nbrBriefRendu = 0;
        $nbrBriefValide = 0;
        $nbrBriefAssigne = 0;
        $nbrBriefNonValide = 0;

        foreach ($briefPromotions as $value) {
            $briefApprenant = $repoBriefApprenant->findOneBySomeField($value->getId(), $id_apprenant);
            if ($briefApprenant) {
                if ($briefApprenant->getStatut()->getLibelle() == "RENDU") {
                    $nbrBriefRendu =  $nbrBriefRendu + 1;
                } elseif ($briefApprenant->getStatut()->getLibelle() == "ASSIGNE") {
                    $nbrBriefAssigne =  $nbrBriefAssigne + 1;
                } elseif ($briefApprenant->getStatut()->getLibelle() == "VALIDE") {
                    $nbrBriefValide = $nbrBriefValide + 1;
                } elseif ($briefApprenant->getStatut()->getLibelle() == "NON VALIDE") {
                    $nbrBriefNonValide = $nbrBriefNonValide + 1;
                }
            }
        }

        $data = $serializer->serialize(["nom"=>$apprenant->getUser()->getLastname(),"Prenom"=>$apprenant->getUser()->getFirstname(),"nbrBriefRendu"=>$nbrBriefRendu,"nbrBriefValide"=>$nbrBriefValide,"nbrBriefAssigne"=>$nbrBriefAssigne,"nbrBriefNonValide"=>$nbrBriefNonValide],"json");
        return new JsonResponse($data, Response::HTTP_OK, [], true);
        
    }

    /**
     * @Route("/formateurs/promo/{id_promo}/referentiel/{id_ref}/statistiques/competences", name="show_statistiques_by_competences")
     */
    public function showStatistiquesByCompetences(SerializerInterface $serializer, int $id_promo, int $id_ref,ReferentielRepository $repoReferentiel, PromotionRepository $repoPromo, CompetenceValideRepository $repoComptenceValide)
    {
        $promo = $repoPromo->find($id_promo);
        $referentiel = $repoReferentiel->find($id_ref);

        
        //$competenceValide = $repoComptenceValide->findOneBySomeField($id_promo,$id_ref);
        //dd($competenceValide);
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
        return new JsonResponse("Commentaire ajouté avec succes", Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/apprenant/livrablepartiels/{id}/commentaires", name="post_commentaire_by_apprenant")
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
        return new JsonResponse("Commentaire ajouté avec succes", Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/formateurs/promotion/{id_promo}/brief/{id_brief}/livrablepartiels", name="put_livrable_partiel_by_formateur")
     */
    public function  putLivrablePartielByFormateur(\Swift_Mailer $mailer, SerializerInterface $serializer, int $id_promo, int $id_brief, Request $request, EntityManagerInterface $em, GroupeRepository $repoGroupe, StatutLivrableRepository $repoStatut, ApprenantRepository $repoApprenant, NiveauEvaluationRepository $repoNiveau, PromotionRepository $repoPromo, BriefRepository $repoBrief, LivrablePartielRepository $repoLivrablePartiels)
    {

        $data = json_decode($request->getContent(), true);
        $promo = $repoPromo->find($id_promo);
        if (empty($promo)) {
            return new JsonResponse("Ce promo n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $brief = $repoBrief->find($id_brief);
        if (empty($brief)) {
            return new JsonResponse("Ce brief n'existe pas .", Response::HTTP_NOT_FOUND, [], true);
        }

        $trouve = false;
        $briefPromotions = $brief->getBriefPromotions();
        foreach ($briefPromotions as $value) {
            if ($value->getPromotion() == $promo) {
                $trouve = true;
                $briefPromotion = $value;
                break;
            }
        }
        if (!$trouve) {
            return new JsonResponse("Ce brief n'appartient pas à cette promotion.", Response::HTTP_NOT_FOUND, [], true);
        }

        /**Archivage */
        if (isset($data['livrablePartiel']) && !empty($data['livrablePartiel'])) {
            if (isset($data['deleted']) && $data['deleted']) {
                $livrablePartiel = $repoLivrablePartiels->find($data['livrablePartiel']);
                $livrablePartiel->setDeleted(true);
                $briefPromotion->removeLivrablePartiel($livrablePartiel);
                $em->flush();
                return new JsonResponse('Livrable Partiel archivé.', Response::HTTP_NO_CONTENT, [], true);
            }
        }

        /**Ajout de Livrable partiels */
        if (empty($data['libelle'])) {
            return new JsonResponse('Le libelle est requis.', Response::HTTP_BAD_REQUEST, [], true);
        }

        if (empty($data['description'])) {
            return new JsonResponse('La description est requise.', Response::HTTP_BAD_REQUEST, [], true);
        }

        if (empty($data['dateSoumission'])) {
            return new JsonResponse('La date de soumission est requise.', Response::HTTP_BAD_REQUEST, [], true);
        }

        $niveaux = $data['niveauCompetences'];
        if (count($niveaux) < 0) {
            return new JsonResponse('Un niveau est requis.', Response::HTTP_BAD_REQUEST, [], true);
        }

        $type = "individuel";
        if (isset($data['apprenants'])) {
            $type = 'individuel';
            $apprenants = $data['apprenants'];
            if (count($apprenants) < 0) {
                return new JsonResponse('Veuillez affecter ce livrable à des apprenants.', Response::HTTP_BAD_REQUEST, [], true);
            }
        }

        if (isset($data['groupe'])) {
            $groupe = $repoGroupe->find($data['groupe']);
            $apprenants = $groupe->getApprenants();
            $type = "groupe";
            if (empty($data['groupe'])) {
                return new JsonResponse('Veuillez affecter ce livrable à des apprenants.', Response::HTTP_BAD_REQUEST, [], true);
            }
        }


        $livrablePartiel =  $serializer->denormalize($data, LivrablePartiel::class, true, ["groups" => "livrable:write"]);
        $livrablePartiel->setDateAffectation(new \DateTime());
        $livrablePartiel->setType($type);


        // Traitement niveaux
        foreach ($niveaux as  $value) {
            $niveauEvaluation = $repoNiveau->find($value);
            if ($niveauEvaluation) {
                $livrablePartiel->addNiveauCompetence($niveauEvaluation);
            }
        }

        // Traitement prom et brief
        $briefPromotion->addLivrablePartiel($livrablePartiel);

        $delai = date_create_from_format('Y-m-d', $data['dateSoumission']);


        //Traitement des affectations
        foreach ($apprenants as  $value) {
            $apprenant = $repoApprenant->find($value);
            if ($apprenant) {
                $livrableRendu = new LivrableRendu();
                $livrableRendu->setApprenant($apprenant);
                $livrableRendu->setStatut($repoStatut->findBy(array('libelle' => 'ASSIGNE'))[0]);
                $livrableRendu->setDelai($livrablePartiel->getDateSoumission());
                $livrablePartiel->addLivrableRendu($livrableRendu);
                $msg = (new \Swift_Message('Sonatel Academy'))
                    ->setFrom('dioufbadaraalioune7@gmail.com')
                    ->setTo($apprenant->getUser()->getEmail())
                    ->setBody("Vous avez été assigné au Livrable Partiels " . $livrablePartiel->getLibelle() . ".Veuillez vous connecter sur la plateforme pour voir les détails.");
                $mailer->send($msg);
            }
        }



        $em->persist($livrablePartiel);
        $em->flush();
        return new JsonResponse("Success", Response::HTTP_CREATED, [], true);
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
                if (isset($data['delai']) || !empty($data['delai'])) {
                    $value->setDelai(date_create_from_format('Y-m-d', $data['delai']));
                }
                $em->persist($value);
            }
        }

        $em->flush();
        return new JsonResponse("Statut Changé", Response::HTTP_CREATED, [], true);
    }




    /**Fonction traitement image */
    public function uploadFile($file, $name)
    {
        $fileType = explode("/", $file->getMimeType())[1];
        $filePath = $file->getRealPath();
        return file_get_contents($filePath, $name . '.' . $fileType);
    }
}
