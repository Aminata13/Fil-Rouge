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
    FormateurRepository $repoFormateur,
    StatutRepository $repoStatus,
    ReferentielRepository $reporef,
    LangueRepository $repoLangue,
    FabriqueRepository $repoFabrique,
    SerializerInterface $serializer,
    Request $request,
    ValidatorInterface $validator, 
    EntityManagerInterface $em, 
    UserPasswordEncoderInterface $encoder,
    \Swift_Mailer $mailer
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

        // // Traitement Apprenants
        // $emailApprenat = array();
        // if ( !empty($request->files->get('fichier')) ){
        //     $filename = $request->files->get('fichier')->getRealPath();
        //     $emailApprenatEx = $this->readFileExcel($filename);
        //     foreach ($emailApprenatEx as $value) {
        //         $emailApprenat[] = $value[0];
        //     }
        // }

        // if (count($promotionTab['apprenants'])>0) {
        //     foreach ($promotionTab['apprenants'] as $value) {
        //         if (!in_array($value,$emailApprenat)) {
        //             $emailApprenat[] = $value;
        //         }
        //     }
        // }

        // if (count($emailApprenat)<1) {
        //     return new JsonResponse("Les Apprenants sont obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        // }

        // foreach ($emailApprenat as $value) {
        //     if (!empty($value)){
        //         $apprenant = new Apprenant();
        //         $apprenant->setEmail($value);
        //         $password = "1234-".$value[4].$value[0].$value[3];
        //         $apprenant->setPassword($encoder->encodePassword(new User(),$password));
        //         $apprenant->setStatut($repoStatus->find(1));
        //         if ($promotion->addApprenant($apprenant)) {
        //             $groupe->addApprenant($apprenant);
        //             $apprenant->sendEmail($mailer ,$password);
        //         }
        //     }
        // }

        // // Traitement Formateur
        // if ( !isset($promotionTab['formateurs']) || empty($promotionTab['formateurs'])) {
        //     return new JsonResponse("Les formateurs sont obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        // }
        // foreach ($promotionTab['formateurs'] as $value) {
        //     if (!empty($value)){
        //         $formateurs = $repoFormateur->find($value);
        //         $promotion->addFormateur($formateurs); 
        //     }
        // }

        
        // L'insertion du promotion
        if ($editPromotion) {
            $em->persist($editPromotion);
            $em->persist($editPromotion);
            $em->flush();
        }

        return new JsonResponse('success', Response::HTTP_CREATED, [], true);
    }

    public function editPromotionApprenats(){

        eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1OTcyMzYwNjAsImV4cCI6MTU5NzIzOTY2MCwicm9sZXMiOlsiUk9MRV9BRE1JTiJdLCJ1c2VybmFtZSI6ImFkbWluMSJ9.k_9wDltIz9YzwUwkvtD9uUbraj6fs7MbJE2V6u_IPoUFb1wEseNPAXZArQNd2deyuhebp24BgyH1MbIBCATJxZ8CAyxcS5r2PM8IM6pbnkSo94Wwvubnz1D4JXFs5hgTxHiAedqUU4p9qS7oRyCoyYL_KnLFN95qFx7-kwxlscuRI83IbDDKWYSBNErVH6h365pNEIvOiCZ5K0JMa9Pt0xPwfXmLG2NV3BIgv-7_zbqMtr6SLxi7EQ02bEZaUJzlduVzxR4rNDlCFU8fqomWyYQJVM-bjH9eEjUEAZTmT0GF0OU1BiIt0d1cJib-KKiR_VR0i57VaPUivrzYJCoLJjPWlwF_Qfpxny2QX8lNlCsMqnXN_5SjQUW_EfU4FVc_be4IAs1XGR1T4HsX7a2CzPbaZwkYtvJvDAy-4KHb90DYH5nMHQ42nmdS4tW7p0SaZ9WUUwxt3_-N_W65ToWQnuOL0j2_THo4aOJh5maUH6oDwfzCRJEU4DsnKB8JBYcaadmIeURmIykDXBsLe_ge1h2TZMdRMFO1b5qAc-nY841QJD-0ERAH2GB8Muh1Gs4W089HpgAw91t7rZ-Cbx2Y6WAAO7C58uyFCqvn06HWPdh8-Q5tFTx7Sw8qd6lIi8eLc-v8E_9d_eBSEzBvFlEP_0cmA4ZB7jqdOAxU4hAknVo
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
