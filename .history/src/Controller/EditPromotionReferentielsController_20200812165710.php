<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Groupe;
use App\Entity\Apprenant;
use App\Entity\Promotion;
use App\Repository\LangueRepository;
use App\Repository\FabriqueRepository;
use App\Repository\UserProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Repository\FormateurRepository;
use App\Repository\PromotionRepository;
use App\Repository\ReferentielRepository;
use App\Repository\StatutRepository;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

// instantiation, when using it as a component
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


/**
 * @Route("/api")
 */
class EditPromotionReferentielsController extends AbstractController
{
    /**
     * @Route("/admin/promotion/{id}/referentiels", name="edit_promotion_referentiles", methods="PUT")
     */
    public function editPromotionRef(
    int $id,
    PromotionRepository $repoPromotion,
    ReferentielRepository $reporef,
    LangueRepository $repoLangue,
    FabriqueRepository $repoFabrique,
    SerializerInterface $serializer,
    Request $request,
    EntityManagerInterface $em
    )
    {
        $editPromotion = $repoPromotion->find($id);
        $promotionTab = $request->request->all();
        $promotion = $serializer->denormalize($promotionTab, Promotion::class, true,["groups"=>"promotion:write"]);

        // Verification des dates et modification des date 
        if ($promotion->getDateDebut()>$promotion->getDateFin()) {
            return new JsonResponse("La date de fin doit etre superieur a la date de debut.", Response::HTTP_BAD_REQUEST, [], true);
        }
        $editPromotion->setDateDebut($promotion->getDateDebut());
        $editPromotion->setDateFin($promotion->getDateFin());
        $editPromotion->setTitre($promotion->getTitre());
        $editPromotion->setDescription($promotion->getDescription());
        $editPromotion->setLieu($promotion->getLieu());
        $editPromotion->setReferenceAgate($promotion->getReferenceAgate());
        
        // Traitement Langue
        if (empty($promotionTab['langue'])) {
            return new JsonResponse("La langue est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $editPromotion->setLangue($repoLangue->findBy(array('libelle' => $promotionTab['langue']))[0]);
        
        // Traitement Fabrique
        if ( !isset($promotionTab['fabrique']) || empty($promotionTab['fabrique'])) {
            return new JsonResponse("La langue est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $editPromotion->setFabrique($repoFabrique->findBy(array('libelle' => $promotionTab['fabrique']))[0]);
        
        // Traitement Groupes
        // if ( !isset($promotionTab['groupes']) || empty($promotionTab['groupes'])) {
        //     return new JsonResponse("Le groupe est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        // }
        // $groupe = new Groupe();
        // $groupe->setLibelle($promotionTab['groupes']);
        // $groupe->setDateCreation(new \DateTime());
        // $promotion->addGroupe($groupe);


        // Traitement referentiels
        foreach ($editPromotion->getReferentiels() as $value) {
            $editPromotion->removeReferentiel($value);
        }
        foreach ($promotionTab['referentiels'] as $value){
            if (!empty($value)){               
                $referentiel = $reporef->findBy(array('libelle' =>$value));
                $editPromotion->addReferentiel($referentiel[0]);
            }
        }
        if (count($editPromotion->getReferentiels())<1) {
            return new JsonResponse("Les referentiels sont obligatoires", Response::HTTP_BAD_REQUEST, [], true);
        }

        // Traitement Image
        $image = $request->files;
        if (!is_null($image->get('image'))) {
            $imageType = explode("/", $image->get('image')->getMimeType())[1];
            $imagePath = $image->get('image')->getRealPath();
            $image = file_get_contents($imagePath, 'img/img.' . $imageType);
            $editPromotion->setimage($image);
        }    
        
        // L'insertion du promotion
        if ($editPromotion) {
            $em->persist($editPromotion);
            $em->persist($editPromotion);
            $em->flush();
        }

        return new JsonResponse('success', Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/admin/promotion/{id}/apprenants", name="edit_promotion_apprenants", methods="PUT")
     */
    public function editPromotionApprenats(
        int $id,
        PromotionRepository $repoPromotion,
        StatutRepository $repoStatus,
        Request $request,
        EntityManagerInterface $em, 
        UserPasswordEncoderInterface $encoder,
        \Swift_Mailer $mailer
    ){
        $editPromotion = $repoPromotion->find($id);
        $apprenantExixtante = $editPromotion->getApprenants();
        $newApprenants = $request->request->all();
        $emailApprenatExistants = array();

        foreach ($editPromotion->getApprenants() as $value){
            $emailApprenatExistants[] = $value->getEmail();
            //$editPromotion->removeApprenant($value);
        }

        $groupPrincipal = null;
        foreach ($editPromotion->getGroupes() as $value) {
            if ($value->getLibelle()=="groupe principale"){
                $groupPrincipal = $value;
                $editPromotion->removeGroupe($value);
            }
        }



        // enlever les apprenants supprimer
        foreach ($apprenantExixtante as $value){
            if (!in_array($value->getEmail(),$newApprenants['apprenants'])){
                $editPromotion->removeApprenant($value);
                $groupPrincipal->removeApprenant($value);
                // on peut mm suppimer l'apprenant de la base de donne 
            }
        }

        // recuperation des apprenants
        $emailApprenat = array();
        if ( !empty($request->files->get('fichier'))){
            $filename = $request->files->get('fichier')->getRealPath();
            $emailApprenatEx = $this->readFileExcel($filename);
            foreach ($emailApprenatEx as $value) {
                if (!in_array($value[0],$emailApprenatExistants)) {
                    $emailApprenat[] = $value[0];
                }
            }
        }
        if (count($newApprenants['apprenants'])>0){
            foreach ($newApprenants['apprenants'] as $value) {
                if (!in_array($value,$emailApprenat) && !in_array($value,$emailApprenatExistants )){
                    $emailApprenat[] = $value;
                }
            }
        }



        // ajout des nouveau apprenant
        foreach ($emailApprenat as $value) {
            if (!empty($value)){
                $apprenant = new Apprenant();
                $apprenant->setEmail($value);
                $password = "1234-".$value[4].$value[0].$value[3];
                $apprenant->setPassword($encoder->encodePassword(new User(),$password));
                $apprenant->setStatut($repoStatus->find(1));
                if ($editPromotion->addApprenant($apprenant)){
                    $groupPrincipal->addApprenant($apprenant);
                    $apprenant->sendEmail($mailer ,$password);
                }
            }
        }

        $editPromotion->addGroupe($groupPrincipal);

        // L'insertion du promotion
        if ($editPromotion) {
            $em->persist($editPromotion);
            $em->persist($editPromotion);
            $em->flush();
        }

        return new JsonResponse('success', Response::HTTP_CREATED, [], true);

        
    }

    public function readFileExcel($filename){
        $reader = \PHPExcel_IOFactory::createReaderForFile($filename);
        // Need this otherwise dates and such are returned formatted
        /** @noinspection PhpUndefinedMethodInspection */
        $reader->setReadDataOnly(true);
        // Just grab all the rows
        $wb = $reader->load($filename);
        $ws = $wb->getSheet(0);
        $rows = $ws->toArray();
        return $rows ;
    }
}
