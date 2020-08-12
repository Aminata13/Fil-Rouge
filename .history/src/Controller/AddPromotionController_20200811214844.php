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
class AddPromotionController extends AbstractController
{
    /**
     * @Route("/admin/promotion", name="add_promotion", methods="POST")
     */
    public function addUser(FormateurRepository $repoFormateur,
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

        $promotionTab = $request->request->all();
        $promotion = $serializer->denormalize($promotionTab, Promotion::class, true,["groups"=>"promotion:write"]);
        
        // Verification des dates
        if ($promotion->getDateDebut()>$promotion->getDateFin()) {
            return new JsonResponse("La date de fin doit etre superieur a la date de debut.", Response::HTTP_BAD_REQUEST, [], true);

        }

        // Traitement Langue
        if (empty($promotionTab['langue'])) {
            return new JsonResponse("La langue est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $promotion->setLangue($repoLangue->findBy(array('libelle' => $promotionTab['langue']))[0]);
        
        // Traitement Fabrique
        if ( !isset($promotionTab['fabrique']) || empty($promotionTab['fabrique'])) {
            return new JsonResponse("La langue est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $promotion->setFabrique($repoFabrique->findBy(array('libelle' => $promotionTab['fabrique']))[0]);
        
        // Traitement Groupes
        if ( !isset($promotionTab['groupes']) || empty($promotionTab['groupes'])) {
            return new JsonResponse("Le groupe est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $groupe = new Groupe();
        $groupe->setLibelle($promotionTab['groupes']);
        $groupe->setDateCreation(new \DateTime());
        $promotion->addGroupe($groupe);

        // Traitement referentiels
        foreach ($promotionTab['referentiels'] as $value){
            if (!empty($value)){               
                $referentiel = $reporef->findBy(array('libelle' =>$value));
                $promotion->addReferentiel($referentiel[0]);
            }
        }

        // Traitement Image
        $image = $request->files;
        if (is_null($image->get('image'))) {
            return new JsonResponse("L'image est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $imageType = explode("/", $image->get('image')->getMimeType())[1];
        $imagePath = $image->get('image')->getRealPath();

        $image = file_get_contents($imagePath, 'img/img.' . $imageType);
        $promotion->setimage($image);
        
        // Traitement Apprenants
        if ( !isset($promotionTab['apprenants']) || empty($promotionTab['apprenants'])) {
            return new JsonResponse("Les Apprenants sont obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }

        $emailApprenat = array();

        if (count($promotionTab['apprenants'])>0) {
            
        }

        $filename = $request->files->get('fichier')->getRealPath();
        $data = $this->readFileExcel($filename);

        if () {
            # code...
        }
        dd($data);

        foreach ($emailApprenat as $value) {
            if (!empty($value)){
                $apprenant = new Apprenant();
                $apprenant->setEmail($value);
                $password = "1234-".$value[4].$value[0].$value[3];
                $apprenant->setPassword($encoder->encodePassword(new User(),$password));
                $apprenant->setStatut($repoStatus->find(1));
                if ($promotion->addApprenant($apprenant)) {
                    $groupe->addApprenant($apprenant);
                    $apprenant->sendEmail($mailer ,$password);
                }
            }
        }



        // Traitement Formateur
        if ( !isset($promotionTab['formateurs']) || empty($promotionTab['formateurs'])) {
            return new JsonResponse("Les formateurs sont obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        foreach ($promotionTab['formateurs'] as $value) {
            if (!empty($value)){
                $formateurs = $repoFormateur->find($value);
                $promotion->addFormateur($formateurs); 
            }
        }

        //dd($promotion->getLieu());

        // L'insertion du promotion
        if ($promotion) {
            $em->persist($promotion);
            $em->persist($promotion);
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
