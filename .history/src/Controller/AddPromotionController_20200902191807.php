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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Repository\FormateurRepository;
use App\Repository\PromotionRepository;
use App\Repository\ReferentielRepository;
use App\Repository\StatutRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
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
    public function addPromotion(
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
        \Swift_Mailer $mailer,
        UserProfilRepository $repoProfil
    ) {

        

        $promotionTab = $request->request->all();
        $promotion = $serializer->denormalize($promotionTab, Promotion::class, true, ["groups" => "promotion:write"]);

        
        // Verification des dates -----------------------
        $promotion->setDateDebut(new \DateTime());
        if ($promotion->getDateDebut() > $promotion->getDateFin()) {
            return new JsonResponse("La date de fin doit etre superieur a la date de debut.", Response::HTTP_BAD_REQUEST, [], true);
        }
        
        // Traitement Langue ----------------------------
        if (empty($promotionTab['langue'])) {
            return new JsonResponse("La langue est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $promotion->setLangue($repoLangue->findBy(array('libelle' => $promotionTab['langue']))[0]);

        // Traitement Fabrique ----------------------
        if (!isset($promotionTab['fabrique']) || empty($promotionTab['fabrique'])) {
            return new JsonResponse("La langue est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $promotion->setFabrique($repoFabrique->findBy(array('libelle' => $promotionTab['fabrique']))[0]);
        
        // Traitement Groupes ---------------------
        $groupe = new Groupe();
        $groupe->setLibelle("Groupe principal");
        $groupe->setDateCreation(new \DateTime());
        $promotion->addGroupe($groupe);

        // Traitement referentiels --------------------
        foreach ($promotionTab['referentiels'] as $value) {
            if (!empty($value)) {
                $referentiel = $reporef->findBy(array('libelle' => $value));
                $promotion->addReferentiel($referentiel[0]);
            }
        }
        dd($promotion);
        // Traitement Image --------------------
        $image = $request->files;
        if (is_null($image->get('image'))) {
            return new JsonResponse("L'image est obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        $imageType = explode("/", $image->get('image')->getMimeType())[1];
        $imagePath = $image->get('image')->getRealPath();

        $image = file_get_contents($imagePath, 'img.'.$imageType);
        
        $promotion->setimage($image);

        // Traitement Apprenants ---------------
        $emailApprenat = array();
        if (!empty($request->files->get('fichier'))) {
            $filename = $request->files->get('fichier')->getRealPath();
            $emailApprenatEx = $this->readFileExcel($filename);
            foreach ($emailApprenatEx as $value) {
                $emailApprenat[] = $value[0];
            }
        }
        if (count($promotionTab['apprenants']) > 0) {
            foreach ($promotionTab['apprenants'] as $value) {
                if (!in_array($value, $emailApprenat)) {
                    $emailApprenat[] = $value;
                }
            }
        }
        if (count($emailApprenat) < 1) {
            return new JsonResponse("Les Apprenants sont obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        foreach ($emailApprenat as $value) {
            if (!empty($value)) {
                $apprenant = new Apprenant();
                $user = new User();
                $user->setEmail($value);
                $password = "1234-" . $value[4] . $value[0] . $value[3];
                $user->setPassword($encoder->encodePassword(new User(), $password));
                $user->setUsername(explode("@", $value)[0]);
                $user->setFirstname("firstname");
                $user->setLastname("lastname");
                $user->setProfil($repoProfil->findBy(['libelle' => "APPRENANT"])[0]);
                $apprenant->setStatut($repoStatus->find(1));
                $apprenant->setUser($user);
                if ($promotion->addApprenant($apprenant)) {
                    $groupe->addApprenant($apprenant);
                    $user->sendEmail($mailer, $password);
                }
            }
        }

       
        // Traitement Formateur -----------------------
        if (!isset($promotionTab['formateurs']) || empty($promotionTab['formateurs'])) {
            return new JsonResponse("Les formateurs sont obligatoire", Response::HTTP_BAD_REQUEST, [], true);
        }
        foreach ($promotionTab['formateurs'] as $value) {
            if (!empty($value)) {
                $formateurs = $repoFormateur->find($value);
                $promotion->addFormateur($formateurs);
            }
        }

        
        // validation du promotion ----------------------------
        $errors = $validator->validate($promotion);
        if (($errors) > 0) {
            $errorsString = $serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }

        // L'insertion du promotion -----------------------------
        $em->persist($promotion);
        $em->flush();
        return new JsonResponse('success', Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/admin/promotion/{id_promo}/relanceAll", name="relance_individuel", methods="POST")
     */
    public function relanceAll(int $id_promo, PromotionRepository $repoPromo, \Swift_Mailer $mailer)
    {
        $user = $this->getUser();
        if (!in_array("ROLE_ADMIN", $user->getRoles())) {
            return new JsonResponse('Accès non autorisé', Response::HTTP_LOCKED, [], true);
        }
        $promo =  $repoPromo->find($id_promo);
        foreach ($promo->getApprenants() as $value) {
            if ($value->getAttente()) {
                $password = "1234-" . $value->getUser()->getEmail()[4] . $value->getUser()->getEmail()[0] . $value->getUser()->getEmail()[3];
                $value->getUser()->sendEmail($mailer, $password);
            }
        }
        return new JsonResponse('Effectuer', Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/admin/promotion/{id_promo}/relanceOne/{id_apprenant}", name="relance_groupes", methods="POST")
     */
    public function relanceOne(int $id_promo, int $id_apprenant, PromotionRepository $repoPromo, \Swift_Mailer $mailer)
    {
        $user = $this->getUser();
        if (!in_array("ROLE_ADMIN", $user->getRoles())) {
            return new JsonResponse('Accès non autorisé', Response::HTTP_LOCKED, [], true);
        }
        $promo =  $repoPromo->find($id_promo);
        foreach ($promo->getApprenants() as $value) {
            if ($value->getId() == $id_apprenant && $value->getAttente()) {
                $password = "1234-" . $value->getUser()->getEmail()[4] . $value->getUser()->getEmail()[0] . $value->getUser()->getEmail()[3];
                $value->getUser()->sendEmail($mailer, $password);
                break;
            }
        }
        return new JsonResponse('Effectuer', Response::HTTP_CREATED, [], true);
    }

    public function readFileExcel($filename)
    {
        $reader = \PHPExcel_IOFactory::createReaderForFile($filename);
        $reader->setReadDataOnly(true);
        $wb = $reader->load($filename);
        $ws = $wb->getSheet(0);
        $rows = $ws->toArray();
        return $rows;
    }
}
