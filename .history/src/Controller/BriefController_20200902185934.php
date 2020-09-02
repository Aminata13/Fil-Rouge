<?php

namespace App\Controller;

use App\Entity\Brief;
use App\Entity\Formateur;
use App\Entity\Ressource;
use App\Entity\BriefApprenant;
use App\Entity\BriefPromotion;
use App\Entity\EtatBriefGroupe;
use App\Entity\LivrableAttendu;
use App\Entity\LivrableApprenant;
use App\Repository\TagRepository;
use App\Repository\BriefRepository;
use App\Repository\GroupeRepository;
use App\Repository\ApprenantRepository;
use App\Repository\EtatBriefRepository;
use App\Repository\FormateurRepository;
use App\Repository\PromotionRepository;
use App\Repository\RessourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StatutBriefRepository;
use App\Repository\BriefPromotionRepository;
use App\Repository\LivrableAttenduRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\NiveauEvaluationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Repository\BriefApprenantRepository;
use App\Repository\EtatBriefGroupeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */
class BriefController extends AbstractController
{

    /**
     * @Route("/formateurs/promotions/{id_promo}/groupe/{id_groupe}/briefs", name="show_brief_by_promoId_by_groupe_id", methods="GET")
     */
    public function getBriefByPromoIdByGroupeId(SerializerInterface $serializer, int $id_promo, int $id_groupe, PromotionRepository $repoPromo, BriefRepository $repoBrief, GroupeRepository $ripoGroupe)
    {

        $promo = $repoPromo->find($id_promo);
        if (empty($promo)) {
            return new JsonResponse("Ce promo n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $groupe = $ripoGroupe->find($id_groupe);
        if (empty($groupe) || !$promo->getGroupes()->contains($groupe)) {
            return new JsonResponse("Ce groupe n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $briefJson = $serializer->serialize($groupe, 'json', ["groups" => ["briefGroupe:read"]]);
        return new JsonResponse($briefJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/formateurs/promotions/{id_promo}/briefs", name="show_brief_by_promoId", methods="GET")
     */
    public function getBriefByPromoId(SerializerInterface $serializer, int $id_promo, PromotionRepository $repoPromo, BriefRepository $repoBrief, GroupeRepository $ripoGroupe)
    {
        $promo = $repoPromo->find($id_promo);
        if (empty($promo)) {
            return new JsonResponse("Ce promo n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $promoJson = $serializer->serialize($promo, 'json', ["groups" => ["briefGroupe:read"]]);
        return new JsonResponse($promoJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/formateurs/{id}/briefs/brouillons", name="show_brief_brouillons_formateur", methods="GET")
     */
    public function getBriefBroullonFormateurId(SerializerInterface $serializer, int $id, PromotionRepository $repoPromo, BriefPromotionRepository $repoBriefPromo, FormateurRepository $repoFormateur, BriefRepository $repoBrief, GroupeRepository $ripoGroupe)
    {

        $formateur = $repoFormateur->find($id);
        if (empty($formateur)) {
            return new JsonResponse("Ce formateur n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        foreach ($formateur->getBriefs() as $value) {
            if ($value->getEtatBrief()->getLibelle() != "BROUILLON") {
                $formateur->removeBrief($value);
            }
        }

        if (count($formateur->getBriefs()) < 1) {
            return new JsonResponse("Aucun brief en brouillon.", Response::HTTP_NOT_FOUND, [], true);
        }

        $briefJson = $serializer->serialize($formateur->getBriefs(), 'json', ["groups" => ["briefGroupe:read"]]);
        return new JsonResponse($briefJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/formateurs/{id}/briefs/valide", name="show_brief_valide_formateur", methods="GET")
     */
    public function getBriefValideFormateurId(SerializerInterface $serializer, int $id, PromotionRepository $repoPromo, BriefPromotionRepository $repoBriefPromo, FormateurRepository $repoFormateur, BriefRepository $repoBrief, GroupeRepository $ripoGroupe)
    {

        $formateur = $repoFormateur->find($id);
        if (empty($formateur)) {
            return new JsonResponse("Ce formateur n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        foreach ($formateur->getBriefs() as $value) {
            if ($value->getEtatBrief()->getLibelle() != "VALIDE" && $value->getEtatBrief()->getLibelle() != "NON ASSIGNE") {
                $formateur->removeBrief($value);
            }
        }

        if (count($formateur->getBriefs()) < 1) {
            return new JsonResponse("Aucun brief valide.", Response::HTTP_NOT_FOUND, [], true);
        }

        $briefJson = $serializer->serialize($formateur->getBriefs(), 'json', ["groups" => ["briefGroupe:read"]]);
        return new JsonResponse($briefJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/formateurs/promotions/{id_promo}/briefs/{id_brief}", name="show_promo_id_brief_id", methods="GET")
     */
    public function getBriefByPromo(SerializerInterface $serializer, int $id_promo, int $id_brief, PromotionRepository $repoPromo, BriefRepository $repoBrief)
    {

        $promo = $repoPromo->find($id_promo);
        if (empty($promo)) {
            return new JsonResponse("Ce promo n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $brief = $repoBrief->find($id_brief);

        if (!($brief)) {
            return new JsonResponse("Ce brief n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $containt = false;
        foreach ($promo->getBriefPromotions() as  $value) {
            if ($value->getBrief() == $brief) {
                $containt = true;
            }
        }

        if (!$containt) {
            return new JsonResponse("Ce brief n'est pas dans se promotion.", Response::HTTP_NOT_FOUND, [], true);
        }

        $briefJson = $serializer->serialize($brief, 'json', ["groups" => ["briefGroupe:read"]]);
        return new JsonResponse($briefJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/apprenants/promotions/{id_promo}/briefs", name="show_brief_by_promoId_apprenant", methods="GET")
     */
    public function getBriefByPromoIdApprenant(SerializerInterface $serializer, int $id_promo, PromotionRepository $repoPromo, BriefRepository $repoBrief, GroupeRepository $ripoGroupe)
    {
        $promo = $repoPromo->find($id_promo);
        if (empty($promo)) {
            return new JsonResponse("Ce promo n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        foreach ($promo->getBriefPromotions() as $value) {
            if ($value->getBrief()->getEtatBrief()->getLibelle() != "ASSIGNE") {
                $promo->removeBriefPromotion($value);
            }
        }

        $promoJson = $serializer->serialize($promo, 'json', ["groups" => ["briefGroupe:read"]]);
        return new JsonResponse($promoJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/formateurs/{id_formateur}/promotions/{id_promo}/briefs/{id_brief}", name="show_brief_by_promo_and_formateur", methods="GET")
     */
    public function getBriefByPromoAndFormateur(SerializerInterface $serializer, int $id_formateur, int $id_promo, int $id_brief, PromotionRepository $repoPromo, BriefRepository $repoBrief, FormateurRepository $repoFormateur)
    {

        $formateur = $repoFormateur->find($id_formateur);
        if (is_null($formateur)) {
            return new JsonResponse("Ce formateur n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }
        $promo = $repoPromo->find($id_promo);
        if (is_null($promo)) {
            return new JsonResponse("Cette promotion n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $brief = $repoBrief->find($id_brief);
        if (is_null($brief)) {
            return new JsonResponse("Ce brief n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }
        $trouve = false;
        $briefPromotions = $brief->getBriefPromotions();
        foreach ($briefPromotions as $value) {
            $promoCourant = $value->getPromotion();
            if ($promoCourant == $promo) {
                $trouve = true;
                break;
            }
        }
        if (!$trouve) {
            return new JsonResponse("Ce brief n'existe pas dans cette promotion.", Response::HTTP_NOT_FOUND, [], true);
        }

        if ($brief->getFormateur() != $formateur) {
            return new JsonResponse("Ce brief n'est pas lié à ce formateur.", Response::HTTP_NOT_FOUND, [], true);
        }

        $briefJson = $serializer->serialize($brief, 'json', ["groups" => ["brief:read"]]);
        return new JsonResponse($briefJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/apprenants/{id_apprenant}/promotions/{id_promo}/briefs/{id_brief}", name="show_brief_by_promo_and_apprenant", methods="GET")
     */
    public function getBriefByPromoAndApprenant(SerializerInterface $serializer, int $id_apprenant, int $id_promo, int $id_brief, PromotionRepository $repoPromo, BriefRepository $repoBrief, ApprenantRepository $repoApprenant, StatutBriefRepository $repoStatutBrief)
    {
        $promo = $repoPromo->find($id_promo);
        if (is_null($promo)) {
            return new JsonResponse("Cette promotion n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $apprenant = $repoApprenant->find($id_apprenant);
        if (is_null($apprenant) || $apprenant->getPromotion() != $promo) {
            return new JsonResponse("Cet apprenant n'appartient pas à cette promotion.", Response::HTTP_NOT_FOUND, [], true);
        }

        $brief = $repoBrief->find($id_brief);
        if (is_null($brief)) {
            return new JsonResponse("Ce brief n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $trouve = false;
        $briefPromotions = $brief->getBriefPromotions();
        foreach ($briefPromotions as $value) {
            $promoCourant = $value->getPromotion();
            if ($promoCourant == $promo) {
                $trouve = true;
                break;
            }
        }
        if (!$trouve) {
            return new JsonResponse("Ce brief n'existe pas dans cette promotion.", Response::HTTP_NOT_FOUND, [], true);
        }

        /** On cible seulement l'apprenant concerné avec ses livrables partiels et ses commentaires, on passe par la classe LivrableApprenant */
        $livrableAttendus = $brief->getLivrableAttendus();
        foreach ($livrableAttendus as $value) {
            $livrables = $value->getLivrableApprenants();
            foreach ($livrables as $val) {
                $apprenantCourant = $val->getApprenant();
                if ($apprenantCourant != $apprenant) {
                    $value->removeLivrableApprenant($val);
                }
            }
        }

        /** On cible seulement les groupes auxquels appartient l'apprenant */
        $statutEnCours = $statutEnCours = $repoStatutBrief->findBy(array('libelle' => 'EN COURS'))[0];
        $groupes = $apprenant->getGroupes();
        $etatBriefGroupes = $brief->getEtatBriefGroupes();
        foreach ($groupes as $g) {
            foreach ($etatBriefGroupes as $value) {
                if ($value->getGroupe() != $g || $value->getStatut() != $statutEnCours) {
                    $brief->removeEtatBriefGroupe($value);
                }
            }
        }

        $briefJson = $serializer->serialize($brief, 'json', ["groups" => ["brief_livrable_partiel:read"]]);
        return new JsonResponse($briefJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/apprenants/{id_apprenant}/groupes/{id_groupe}/livrables", name="add_livrables_by_apprenant_and_groupe", methods="POST")
     */
    public function addLivrableApprenants(SerializerInterface $serializer, int $id_apprenant, int $id_groupe, ApprenantRepository $repoApprenant, LivrableAttenduRepository $repoLivrableAttendu, Request $request, EntityManagerInterface $em)
    {
        $data = json_decode($request->getContent(), true);

        $apprenant = $repoApprenant->find($id_apprenant);
        if (empty($apprenant)) {
            return new JsonResponse("Cet apprenant n'est pas repertorié sur le système.", Response::HTTP_NOT_FOUND, [], true);
        }

        $groupe = $repoGroupe->find($id_groupe);
        if (empty($groupe)) {
            return new JsonResponse("Ce groupe n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $trouve = false;
        $apprenants = $groupe->getApprenants();
        foreach ($apprenants as $value) {
            if ($value == $apprenant) {
                $trouve = true;
                break;
            }
        }
        if (!$trouve) {
            return new JsonResponse("Cet apprenant n'appartient pas à ce groupe.", Response::HTTP_NOT_FOUND, [], true);
        }

        $tabLibelle = [];
        foreach ($apprenants as $a) {
            foreach ($data as $key => $value) {
                $livrableApprenant = new LivrableApprenant();
                $livrableApprenant->setUrl($value);
                $livrableApprenant->setApprenant($a);

                $livrableAttendu = $repoLivrableAttendu->findBy(array('libelle' => $key));
                if ($livrableAttendu) {
                    $livrableApprenant->setLivrableAttendu($livrableAttendu[0]);
                } else {
                    if (!in_array($value, $tabLibelle)) {
                        $tabLibelle[] = $value;
                        $livrableAttendu = new LivrableAttendu();
                        $livrableAttendu->setLibelle($key);
                        $livrableApprenant->setLivrableAttendu($livrableAttendu);
                    }
                }

                $em->persist($livrableApprenant);
                $em->flush();
            }
        }

        return new JsonResponse("Livrables enregistrés avec succès.", Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/formateurs/briefs/{id}", name="duplicate_brief", methods="POST")
     */
    public function duplicateBrief(int $id, BriefRepository $repoBrief, TagRepository $repoTag, LivrableAttenduRepository $repoLivrableAttendu, FormateurRepository $repoFormateur, NiveauEvaluationRepository $repoNiveauEvaluation, RessourceRepository $repoRess, EtatBriefRepository $repoEtatBrief, EntityManagerInterface $em)
    {
        $brief = $repoBrief->find($id);
        if (empty($brief)) {
            return new JsonResponse("Ce brief n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $newBrief = new Brief();

        $newBrief->setLangue($brief->getLangue());
        $newBrief->setTitre($brief->getTitre());
        $newBrief->setDescription($brief->getDescription());
        $newBrief->setContexte($brief->getContexte());
        $newBrief->setModalitePedagogique($brief->getModalitePedagogique());
        $newBrief->setCriterePerformance($brief->getCriterePerformance());
        $newBrief->setModaliteEvaluation($brief->getModaliteEvaluation());
        $newBrief->setImage($brief->getImage());
        $newBrief->setDateCreation(new \DateTime());
        $newBrief->setReferentiel($brief->getReferentiel());
        $newBrief->setLivrables($brief->getLivrables());

        foreach ($brief->getRessource() as $r) {
            $ressource = $repoRess->find($r->getId());
            $newBrief->addRessource($ressource);
        }
        foreach ($brief->getNiveauCompetences() as $n) {
            $niveau = $repoNiveauEvaluation->find($n->getId());
            $newBrief->addNiveauCompetence($niveau);
        }
        foreach ($brief->getTags() as $t) {
            $tag = $repoTag->find($t->getId());
            $newBrief->addTag($tag);
        }
        foreach ($brief->getLivrableAttendus() as $l) {
            $livrableAttendu = $repoLivrableAttendu->find($l->getId());
            $newBrief->addLivrableAttendu($livrableAttendu);
        }

        /**Récupération du formateur connecté */
        $user = $this->getUser()->getId();
        $formateur = $repoFormateur->findBy(array('user' => $user))[0];
        $newBrief->setFormateur($formateur);

        $etat = $repoEtatBrief->findBy(array('libelle' => 'COMPLET'));
        $newBrief->setEtatBrief($etat[0]);


        $em->persist($newBrief);
        $em->flush();

        return new JsonResponse("Brief dupliqué avec succès.", Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/formateurs/briefs", name="add_brief", methods="POST")
     */
    public function addBrief(SerializerInterface $serializer, ValidatorInterface $validator, StatutBriefRepository $repoStatutBrief, GroupeRepository $repoGroupe, EtatBriefRepository $repoEtatBrief, FormateurRepository $repoFormateur, LivrableAttenduRepository $repoLivrableAttendu, EntityManagerInterface $em, Request $request, \Swift_Mailer $mailer)
    {

        $data = $request->request->all();

        /**Recupération référentiel */
        $referentielIri = 'api/admin/referentiels/' . $data["referentiel"];
        if (isset($data["referentiel"])) {
            $data["referentiel"] = $referentielIri;
        }

        /**Récupération langue */
        $langueIri = 'api/langues/' . $data["langue"];
        if (isset($data["langue"])) {
            $data["langue"] = $langueIri;
        }

        /**Récupération des compétences et des niveaux */
        if (isset($data['niveauCompetences'])) {
            foreach ($data['niveauCompetences'] as $key => $value) {
                $data['niveauCompetences'][$key] = 'api/niveau_evaluations/' . $value;
            }
        }
        
        /**Récupération des tags */
        if (isset($data['tags'])) {
            foreach ($data['tags'] as $key => $value) {
                $data['tags'][$key] = 'api/admin/tags/' . $value;
            }
        }
        
        /**Récupération du formateur connecté */
        $user = $this->getUser()->getId();
        $formateur = $repoFormateur->findBy(array('user' => $user));

        /** Dénormalisation en briefs */
        $brief = $serializer->denormalize($data, Brief::class, true, ["groups" => "brief:write"]);
        $brief->setFormateur($formateur[0]);
        $brief->setDateCreation(new \DateTime());
        
        /** Traitement de l'image et des pieces jointes */
        if (count($request->files) != 0){
            foreach ($request->files as $key => $value){
                if ($key == 'image'){
                    $brief->setImage($this->uploadFile($value, 'image'));
                } else{
                    $ressourceTab = $value;
                    foreach ($ressourceTab as $value){
                        $ressource = new Ressource();
                        $pieceJointe = $this->uploadFile($value, 'ressource');
                        $ressource->setPieceJointe($pieceJointe);
                        $brief->addRessource($ressource);
                    }
                }
            }
        }

        
        /** Traitement des ressources de type URL */
        if (isset($data['ressource'])) {
            foreach ($data['ressource'] as $value) {
                $ressource = new Ressource();
                $ressource->setUrl($value);
                $brief->addRessource($ressource);
            }
        }

        /** Traitement des livrables attendus: on les rattache au brief ou on les crée si nécessaire */
        if (isset($data['livrableAttendus'])) {
            $tabLibelle = [];
            foreach ($data['livrableAttendus'] as $value) {
                $livrableAttendu = $repoLivrableAttendu->findBy(array('libelle' => $value));
                if ($livrableAttendu) {
                    $brief->addLivrableAttendu($livrableAttendu[0]);
                } else {
                    if (!in_array($value, $tabLibelle)) {
                        $tabLibelle[] = $value;
                        $livrableAttendu = new LivrableAttendu();
                        $livrableAttendu->setLibelle($value);
                        $brief->addLivrableAttendu($livrableAttendu);
                    }
                }
            }
        }

        /** Affecter EtatBrief */
        $errors = $validator->validate($brief);

        if (($errors) > 0) {
            $errorsString = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }
        if (isset($data['groupes'])) {
            $etat = $repoEtatBrief->findBy(array('libelle' => 'ASSIGNE'));
        } elseif (isset($data['referentiel']) && isset($data['niveauCompetences']) && isset($data['tags'])) {
            $etat = $repoEtatBrief->findBy(array('libelle' => 'COMPLET'));
        } else {
            $etat = $repoEtatBrief->findBy(array('libelle' => 'BROUILLON'));
        }
        $brief->setEtatBrief($etat[0]);
        
        /** Assignation du brief à un groupe */
        if (isset($data['groupes'])) {
            foreach ($data['groupes'] as $value) {
                $groupe = $repoGroupe->find($value);
                
                /** Implémentation de EtatBriefGroupe */
                $etatBriefGroupe = new EtatBriefGroupe;
                $etatBriefGroupe->setBrief($brief);
                $etatBriefGroupe->setGroupe($groupe);
                $statut = $repoStatutBrief->findBy(array('libelle' => 'EN COURS'));
                $etatBriefGroupe->setStatut($statut[0]);
                
                /** Implementation de BriefPromotion */
                $briefPromo = new BriefPromotion();
                $promo = $groupe->getPromotion();
                $briefPromo->setPromotion($promo);
                $briefPromo->setBrief($brief);
                $briefPromo->setStatut($statut[0]);
                $brief->addBriefPromotion($briefPromo);
                
                /** Implementation du BriefApprenant */
                foreach ($groupe->getApprenants() as $value) {
                    $briefApprenant = new BriefApprenant();
                }
                
                /** Envoi de mails aux apprenants assignés au brief*/
                foreach ($groupe->getApprenants() as $value) {
                    $this->sendEmail($mailer, $value, $brief);
                }
                
            }
        }
        
        dd($em->persist($brief);
        $em->flush();
        return new JsonResponse("succès.", Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/formateurs/promotions/{id_promo}/briefs/{id_brief}/assignation", name="assign_brief", methods="POST")
     */
    public function assignBrief(int $id_promo, int $id_brief, BriefApprenantRepository $repoBriefApprenant, StatutBriefRepository $repoStatutBrief, ApprenantRepository $repoApprenant, BriefRepository $repoBrief, BriefPromotionRepository $repoBriefPromo, GroupeRepository $repoGroupe, EtatBriefRepository $repoEtatBrief, PromotionRepository $repoPromo, EtatBriefGroupeRepository $repoEtatBriefGroupe, EntityManagerInterface $em, Request $request, \Swift_Mailer $mailer)
    {
        $data = json_decode($request->getContent(), true);

        $promo = $repoPromo->find($id_promo);
        if (is_null($promo)) {
            return new JsonResponse("Cette promotion n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $brief = $repoBrief->find($id_brief);
        if (is_null($brief)) {
            return new JsonResponse("Ce brief n'existe pas .", Response::HTTP_NOT_FOUND, [], true);
        }

        /** Traitement assignation d'un brief à un apprenant, un groupe ou plusieurs groupes */
        if (isset($data['assignation']) && $data['assignation']) {
            $statutAssigne = $repoStatutBrief->findBy(array('libelle' => 'ASSIGNE'))[0];
            $statutEnCours = $repoStatutBrief->findBy(array('libelle' => 'EN COURS'))[0];

            /** Assignation d'un brief à un apprenant */
            if (isset($data['apprenant'])) {
                $apprenant = $repoApprenant->find($data['apprenant']);
                if ($promo->getApprenants()->contains($apprenant)) {
                    /** Implementation de BriefApprenant */
                    $briefApprenant = new BriefApprenant();
                    $briefApprenant->setStatut($statutAssigne);
                    $briefApprenant->setApprenant($apprenant);

                    /** Implementation de BriefPromotion */
                    $briefPromotion = $repoBriefPromo->findBy(array('promotion' => $promo, 'brief' => $brief));

                    if (empty($briefPromotion)) {
                        $briefPromo = new BriefPromotion();
                        $briefPromo->setPromotion($promo);
                        $briefPromo->setBrief($brief);
                        $briefPromo->setStatut($statutEnCours);
                        $briefApprenant->setBriefPromotion($briefPromo);
                    } else {
                        $briefApprenant->setBriefPromotion($briefPromotion[0]);
                    }
                    $brief->setEtatBrief($repoEtatBrief->findBy(array('libelle' => 'ASSIGNE'))[0]);
                    $this->sendEmail($mailer, $apprenant, $brief);

                    $em->persist($briefApprenant);
                    $em->flush();
                }

                /** Assignation d'un brief à un groupe ou plusieurs groupes */
            } elseif (isset($data['groupes'])) {
                foreach ($data['groupes'] as $value) {
                    $groupe = $repoGroupe->find($value['id']);
                    $promotion = $groupe->getPromotion();

                    /** Implémentation de EtatBriefGroupe */
                    $etatBriefGroupe = new EtatBriefGroupe;
                    $etatBriefGroupe->setBrief($brief);
                    $etatBriefGroupe->setGroupe($groupe);
                    $etatBriefGroupe->setStatut($statutEnCours);

                    /** Implementation de BriefPromotion */
                    $briefPromotion = $repoBriefPromo->findBy(array('promotion' => $promotion, 'brief' => $brief));

                    if (empty($briefPromotion)) {
                        $briefPromo = new BriefPromotion();
                        $briefPromo->setPromotion($promotion);
                        $briefPromo->setBrief($brief);
                        $briefPromo->setStatut($statutEnCours);

                        $em->persist($briefPromo);
                    } else {
                        $brief->addBriefPromotion($briefPromotion[0]);
                    }
                    $brief->setEtatBrief($repoEtatBrief->findBy(array('libelle' => 'ASSIGNE'))[0]);

                    /** Envoi de mails aux apprenants assignés au brief*/
                    foreach ($groupe->getApprenants() as $value) {
                        $this->sendEmail($mailer, $value, $brief);
                    }

                    $em->persist($etatBriefGroupe);
                    $em->flush();
                }
            }
        }

        /** Traitement desassignation d'un brief à un apprenant, un groupe ou plusieurs groupes */
        if (isset($data['assignation']) && !$data['assignation']) {

            /** Desassignation d'un brief à un apprenant */
            if (isset($data['apprenant'])) {
                $apprenant = $repoApprenant->find($data['apprenant']);
                if ($promo->getApprenants()->contains($apprenant)) {
                    $briefPromotion = $repoBriefPromo->findBy(array('promotion' => $promo, 'brief' => $brief))[0];
                    $briefApprenant = $repoBriefApprenant->findBy(array('briefPromotion' => $briefPromotion, 'apprenant' => $apprenant))[0];
                    $briefApprenant->setBriefPromotion(null);
                    $briefApprenant->setApprenant(null);

                    $this->sendEmailDessasignation($mailer, $apprenant, $brief);

                    $em->remove($value);
                    $em->flush();
                }

                /** desassignation d'un brief à un groupe ou plusieurs groupes */
            } elseif (isset($data['groupes'])) {
                foreach ($data['groupes'] as $value) {
                    $groupe = $repoGroupe->find($value['id']);
                    $etatBriefGroupe = $repoEtatBriefGroupe->findBy(array('groupe' => $groupe, 'brief' => $brief))[0];
                    $etatBriefGroupe->setGroupe(null);
                    $etatBriefGroupe->setBrief(null);

                    foreach ($groupe->getApprenants() as $value) {
                        $this->sendEmailDessasignation($mailer, $value, $brief);
                    }

                    $em->remove($etatBriefGroupe);
                    $em->flush();
                }
            }
        }

        return new JsonResponse("Success", Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/formateurs/promotions/{id_promo}/briefs/{id_brief}", name="edit_brief", methods="PUT")
     */
    public function editBrief(Int $id_brief, Int $id_promo, BriefRepository $repoBrief, EtatBriefGroupeRepository $repoEtatBriefGroupe, BriefPromotionRepository $repoBriefPromo, PromotionRepository $repoPromo, ApprenantRepository $repoApprenant, BriefApprenantRepository $repoBriefApprenant, SerializerInterface $serializer, ValidatorInterface $validator, StatutBriefRepository $repoStatutBrief, TagRepository $repoTag, NiveauEvaluationRepository $repoNiveauEvaluation, RessourceRepository $repoRessource, GroupeRepository $repoGroupe, EtatBriefRepository $repoEtatBrief, LivrableAttenduRepository $repoLivrableAttendu, EntityManagerInterface $em, Request $request)
    {

        $data = $request->request->all();

        $brief = $repoBrief->find($id_brief);
        if (is_null($brief)) {
            return new JsonResponse("Ce brief n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }
        $promo = $repoPromo->find($id_promo);
        if (is_null($promo)) {
            return new JsonResponse("Cette promotion n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        /** Archiver un brief */
        if (isset($data['archive']) && $data['archive']) {
            $brief->setEtatBrief($repoEtatBrief->findBy(array('libelle' => 'ARCHIVE'))[0]);

            foreach ($brief->getBriefPromotions() as $value) {
                $statut = $repoStatutBrief->findBy(array('libelle' => 'CLOTURE'))[0];
                $value->setSatut($statut);
                foreach ($value->getBriefApprenants() as $val) {
                    $val->setSatut($statut);
                }
            }

        }

        /** Cloturer un brief */
        if (isset($data['cloture']) && $data['cloture']) {
            if (isset($data['apprenant'])) {
                $apprenant = $repoApprenant->find($data['apprenant']);
                $briefPromotion = $repoBriefPromo->findBy(array('promotion' => $promo, 'brief' => $brief))[0];
                $briefApprenant = $repoBriefApprenant->findBy(array('briefPromotion' => $briefPromotion, 'apprenant' => $apprenant))[0];

                $briefApprenant->setStatut($statut = $repoStatutBrief->findBy(array('libelle' => 'CLOTURE'))[0]);
            
            } elseif (isset($data['groupe'])) {
                $groupe = $repoGroupe->find($value['id']);
                $etatBriefGroupe = $repoEtatBriefGroupe->findBy(array('groupe' => $groupe, 'brief' => $brief))[0];
                $etatBriefGroupe->setStatut($statut = $repoStatutBrief->findBy(array('libelle' => 'CLOTURE'))[0]);
            }
        }

        /**Modification des compétences et des niveaux */
        if (isset($data['niveauCompetences'])) {
            foreach ($brief->getNiveauCompetences() as $value) {
                $brief->removeNiveauCompetence($value);
            }
            foreach ($data['niveauCompetences'] as $value) {
                $niveauCompetence = $repoNiveauEvaluation->find($value);
                $brief->addNiveauCompetence($niveauCompetence);
            }
        }

        /** Modification des tags */
        if (isset($data['tags'])) {
            foreach ($brief->getTags() as $value) {
                $brief->removeTag($value);
            }
            foreach ($data['tags'] as $value) {
                $tag = $repoTag->find($value);
                $brief->addTag($tag);
            }
        }

        /** Modification des livrables attendus: on les rattache au brief ou on les crée si nécessaire */
        if (isset($data['livrableAttendus'])) {
            foreach ($brief->getLivrableAttendus() as $value) {
                $brief->removeLivrableAttendu($value);
            }

            foreach ($data['livrableAttendus'] as $value) {
                $livrableAttendu = $repoLivrableAttendu->find($value);
                $brief->addLivrableAttendu($livrableAttendu);
            }
        }

        /** Modification des ressources de type URL */
        if (isset($data['ressource'])) {
            foreach ($data['ressource'] as $key => $value) {
                $ressource = $repoRessource->find($key);
                $ressource->setUrl($value);
            }
        }

        /** Modification des ressources de type fichier */
        if (isset($request->files) && $data['ressourceId']) {
            $ressource = $repoRessource->find($data['ressourceId']);
            $ressource->setPieceJointe($this->uploadFile($request->files->get('pieceJointe'), 'ressource'));
        }

        $em->flush();
        return new JsonResponse("succès.", Response::HTTP_CREATED, [], true);
    }

    /**Fonction traitement image */
    public function uploadFile($file, $name)
    {
        $fileType = explode("/", $file->getMimeType())[1];
        $filePath = $file->getRealPath();
        return file_get_contents($filePath, $name . '.' . $fileType);
    }

    /** Send email assignation brief */
    public function sendEmail(\Swift_Mailer $mailer, $user, $brief)
    {
        $msg = (new \Swift_Message('Sonatel Academy'))
            ->setFrom('dioufbadaraalioune7@gmail.com')
            ->setTo($user->getUser()->getEmail())
            ->setBody("Vous avez été assigné au brief " . $brief->getTitre() . ".Veuillez vous connecter sur la plateforme pour voir les détails.");
        $mailer->send($msg);
    }

    /** Send email desassignation brief*/
    public function sendEmailDessasignation(\Swift_Mailer $mailer, $user, $brief)
    {
        $msg = (new \Swift_Message('Sonatel Academy'))
            ->setFrom('dioufbadaraalioune7@gmail.com')
            ->setTo($user->getUser()->getEmail())
            ->setBody("Vous avez été desassigné au brief " . $brief->getTitre() . ".Veuillez vous connecter sur la plateforme pour voir les détails.");
        $mailer->send($msg);
    }
    /**
     * @Route("/formateurs/briefs_test/{id}", name="put_brief", methods="POST")
     */
    public function editImage(RessourceRepository $repoRess, Request $request, BriefRepository $repoBrief, int $id, EntityManagerInterface $em)
    {
        // dd($request->files->get('image'));
        $data = $request->files->get('pieceJointe');
        // $data = $request->files->get('pieceJointe');
        // $brief = $repoBrief->find($id);
         $ressource = $repoRess->find(1);
         $ressource->setPieceJointe($this->uploadFile($data, 'pdf'));

        // $brief->setImage($this->uploadFile($data, 'image'));

        $em->flush();
        return new JsonResponse("succès.", Response::HTTP_CREATED, [], true);
    }
}
