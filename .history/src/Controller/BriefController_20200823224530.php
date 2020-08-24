<?php

namespace App\Controller;

use App\Entity\Brief;
use App\Entity\Ressource;
use App\Entity\LivrableAttendu;
use App\Repository\BriefRepository;
use App\Repository\GroupeRepository;
use App\Repository\EtatBriefRepository;
use App\Repository\FormateurRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BriefPromotionRepository;
use App\Repository\LivrableAttenduRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\BriefPromotion;
use App\Entity\EtatBriefGroupe;
use App\Entity\LivrableApprenant;
use App\Repository\ApprenantRepository;
use App\Repository\StatutBriefRepository;
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
    public function getBriefBroullonFormateurId(SerializerInterface $serializer, int $id, int $id_groupe, PromotionRepository $repoPromo, BriefPromotionRepository $repoBriefPromo, FormateurRepository $repoFormateur, BriefRepository $repoBrief, GroupeRepository $ripoGroupe)
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

        if (empty($formateur->getBriefs())) {
            return new JsonResponse("Aucun brief en brouillon.", Response::HTTP_NOT_FOUND, [], true);
        }

        $briefJson = $serializer->serialize($formateur->getBriefs(), 'json', ["groups" => ["briefGroupe:read"]]);
        return new JsonResponse($briefJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/formateurs/{id}/briefs/valide", name="show_brief_valide_formateur", methods="GET")
     */
    public function getBriefValideFormateurId(SerializerInterface $serializer, int $id, int $id_groupe, PromotionRepository $repoPromo, BriefPromotionRepository $repoBriefPromo, FormateurRepository $repoFormateur, BriefRepository $repoBrief, GroupeRepository $ripoGroupe)
    {

        $formateur = $repoFormateur->find($id);
        if (empty($formateur)) {
            return new JsonResponse("Ce formateur n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        foreach ($formateur->getBriefs() as $value) {
            if ($value->getEtatBrief()->getLibelle() != "COMPLET") {
                $formateur->removeBrief($value);
            }
        }

        if (empty($formateur->getBriefs())) {
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
        if (empty($brief) || !$promo->getGroupes()->contains($brief)) {
            return new JsonResponse("Ce brief n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
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
        if (empty($formateur)) {
            return new JsonResponse("Ce formateur n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }
        $promo = $repoPromo->find($id_promo);
        if (empty($promo)) {
            return new JsonResponse("Ce promo n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $brief = $repoBrief->find($id_brief);
        if (empty($brief) || !$promo->getGroupes()->contains($brief)) {
            return new JsonResponse("Ce brief n'existe pas dans cette promotion.", Response::HTTP_NOT_FOUND, [], true);
        }
        if ($brief->getFormateur() != $formateur) {
            return new JsonResponse("Ce brief n'est pas lié à ce formateur.", Response::HTTP_NOT_FOUND, [], true);
        }

        $briefJson = $serializer->serialize($brief, 'json', ["groups" => ["briefGroupe:read"]]);
        return new JsonResponse($briefJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/apprenants/{id_apprenant}/promotions/{id_promo}/briefs/{id_brief}", name="show_brief_by_promo_and_apprenant", methods="GET")
     */
    public function getBriefByPromoAndApprenant(SerializerInterface $serializer, int $id_apprenant, int $id_promo, int $id_brief, PromotionRepository $repoPromo, BriefRepository $repoBrief, ApprenantRepository $repoApprenant)
    {

        $apprenant = $repoApprenant->find($id_apprenant);
        if (empty($apprenant)) {
            return new JsonResponse("Cet apprenant n'est pas repertorié sur le système.", Response::HTTP_NOT_FOUND, [], true);
        }

        $promo = $repoPromo->find($id_promo);
        if (empty($promo)) {
            return new JsonResponse("Ce promo n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $brief = $repoBrief->find($id_brief);
        if (empty($brief) || !$promo->getGroupes()->contains($brief)) {
            return new JsonResponse("Ce brief n'existe pas dans cette promotion.", Response::HTTP_NOT_FOUND, [], true);
        }

        if ($apprenant->getPromotion() != $promo) {
            return new JsonResponse("Cet apprenant ne fait pas partie de cette promotion.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $trouve = false;
        $briefApprenants = $apprenant->getBriefApprenants();
        foreach ($briefApprenants as $value) {
            $briefCourant = $value->getBriefPromotion()->getBrief();
            if ($briefCourant == $brief) {
                $trouve = true;
                break;
            }
        }
        if (!$trouve) {
            return new JsonResponse("Ce brief n'est pas assigné à cet apprenant.", Response::HTTP_NOT_FOUND, [], true);
        }

        /** On cible seulement l'apprenant concerné avec ses livrables partiels et ses commentaires, on passe par la classe LivrableApprenant */
        $livrableAttendus = $brief->getLivrableAttendus();
        foreach ($livrableAttendus as $value) {
            $apprenantCourant = $value->getLivrableApprenants()->getApprenant();
            if ($apprenantCourant != $apprenant) {
                $value->removeLivrableApprenant();
            }
        }

        $briefJson = $serializer->serialize($brief, 'json', ["groups" => ["brief_livrable_partiel:read"]]);
        return new JsonResponse($briefJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/apprenants/{id_apprenant}/groupes/{id_groupe}/livrables", name="add_livrables_by_apprenant_and_groupe", methods="POST")
     */
    public function addLivrableApprenants(SerializerInterface $serializer, int $id_apprenant, int $id_groupe, GroupeRepository $repoGroupe, ApprenantRepository $repoApprenant, LivrableAttenduRepository $repoLivrableAttendu, Request $request, EntityManagerInterface $em)
    {
        $json = file_get_contents('php://input');

        // Converts it into an associative array
        $data = json_decode($json, true);

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
        $livrableApprenants = [];
        foreach ($data as $key => $value) {
            $livrableApprenant = new LivrableApprenant();
            $livrableApprenant->setUrl($value);
            

            $livrableAttendu = $repoLivrableAttendu->findBy(array('libelle' => $key));
            if ($livrableAttendu) {
                // $livrableAttendu[0]->addLivrableApprenant($livrableApprenant);
                $livrableApprenant->setLivrableAttendu($livrableAttendu[0]);
            } else {
                if (!in_array($value, $tabLibelle)) {
                    $tabLibelle[] = $value;
                    $livrableAttendu = new LivrableAttendu();
                    $livrableAttendu->setLibelle($key);
                    // $livrableAttendu->addLivrableApprenant($livrableApprenant);
                    $livrableApprenant->setLivrableAttendu($livrableAttendu);
                }
            }
            $livrableApprenants[] = $livrableApprenant;
        }
        // dd($livrableApprenants);
        foreach ($livrableApprenants as $value) {
            foreach ($apprenants as $a) {
                $value->setApprenant($a);
                $em->persist($value);
            }
        }
        $em->flush();
        return new JsonResponse("Livrables enregistrés avec succès.", Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/formateurs/briefs/{id}", name="duplicate_brief", methods="POST")
     */
    public function duplicateBrief(int $id, BriefRepository $repoBrief, EtatBriefRepository $repoEtatBrief, EntityManagerInterface $em)
    {
        $brief = $repoBrief->find($id);
        if (empty($brief)) {
            return new JsonResponse("Ce brief n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $newBrief = $brief;
        foreach ($newBrief->getBriefPromotions() as $value) {
            $newBrief->removeBriefPromotion($value);
        }
        $newBrief->setEtatBriefGroupe(null);
        $etat = $repoEtatBrief->findBy(array('libelle' => 'COMPLET'));
        $newBrief->setEtatBrief($etat[0]);
        $newBrief->resetId();

        $em->persist($newBrief);
        $em->flush();

        return new JsonResponse("Brief dupliqué avec succès.", Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/formateurs/briefs", name="add_brief", methods="POST")
     */
    public function addBrief(SerializerInterface $serializer, ValidatorInterface $validator, StatutBriefRepository $repoStatutBrief, BriefRepository $repoBrief, GroupeRepository $repoGroupe, EtatBriefRepository $repoEtatBrief, FormateurRepository $repoFormateur, LivrableAttenduRepository $repoLivrableAttendu, EntityManagerInterface $em, Request $request, \Swift_Mailer $mailer)
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
        if (count($request->files) != 0) {
            foreach ($request->files as $key => $value) {
                if ($key == 'image') {
                    $brief->setImage($this->uploadFile($value, 'image'));
                } else {
                    $ressourceTab = $value;
                    foreach ($ressourceTab as $value) {
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
        dd
        /** Assignation du brief à un groupe */
        if (isset($data['groupes'])) {
            foreach ($data['groupes'] as $value) {
                $groupe = $repoGroupe->find($value);

                /** Implémentation de EtatBriefGroupe */
                $etatBriefGroupe = new EtatBriefGroupe;
                $etatBriefGroupe->addBrief($brief);
                $etatBriefGroupe->addGroupe($groupe);
                $statut = $repoStatutBrief->findBy(array('libelle' => 'ASSIGNE'));
                $etatBriefGroupe->setStatut($statut[0]);

                /** Implementation de BriefPromotion */
                $briefPromo = new BriefPromotion();
                $promo = $groupe->getPromotion();
                $briefPromo->setPromotion($promo);
                $briefPromo->setBrief($brief);
                $briefPromo->setStatut($statut[0]);
                $brief->addBriefPromotion($briefPromo);

                /** Envoi de mails aux apprenants assignés au brief*/
                foreach ($groupe->getApprenants() as $value) {
                    $msg = (new \Swift_Message('Sonatel Academy'))
                        ->setFrom('n.minakey@gmail.com')
                        ->setTo($value->getUser()->getEmail())
                        ->setBody("Vous avez été assigné au brief " . $brief->getTitre() . ".Veuillez vous connecter sur la plateforme pour voir les détails.");
                    $mailer->send($msg);
                }
            }
        }

        $em->persist($brief);
        $em->flush();

        return new JsonResponse("Success", Response::HTTP_CREATED, [], true);
    }

    /**Fonction traitement image */
    public function uploadFile($file, $name)
    {
        $fileType = explode("/", $file->getMimeType())[1];
        $filePath = $file->getRealPath();
        return file_get_contents($filePath, $name . '.' . $fileType);
    }
}
