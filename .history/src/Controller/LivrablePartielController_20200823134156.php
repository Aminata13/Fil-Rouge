<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Repository\ApprenantRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StatutLivrableRepository;
use App\Repository\LivrablePartielRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LivrablePartielController extends AbstractController
{
    /**
     * @Route("/formateurs/promo/id/referentiel/id/competences", name="show_competences_by_apprenant")
     */
    public function showCompetenceByApprenant()
    {
    }

    /**
     * @Route("/apprenant/id/promo/id/referentiel/id/competences", name="show_competences_by_apprenant_id")
     */
    public function showCompetenceByApprenantId()
    {
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
    public function postCommentaireByFormateur(Request $request, EntityManagerInterface $em, int $id_livrable, LivrablePartielRepository $repoLivrablePartiels)
    {
        $data = json_decode($request->getContent(), true);
         
        if (!isset($data['commentaire']) || empty($data['commentaire'])) {
            return new JsonResponse("Veuillez remplir le commentaire.", Response::HTTP_BAD_REQUEST, [], true);
        }
        $commentaire = new Commentaire();
        $commentaire->setLibelle($data['commentaire']);
        $commentaire->setDate(new \DateTime());
        $file =$request->files;
        if (condition) {
            # code...
        }
        $commentaire->setPieceJointe($this->uploadFile($file,"pieceJointe"));
        //dd($commentaire);

        $livrablePartiel = $repoLivrablePartiels->find($id_livrable);

    }

    /**
     * @Route("/apprenant/livrablepartiels/id/commentaires", name="post_commentaire_by_apprenant")
     */
    public function  postCommentaireByApprenant()
    {
    }
    
    /**
     * @Route("/formateurs/promo/id/brief/id/livrablepartiels", name="put_livrable_partiel_by_formateur")
     */
    public function  putLivrablePartielByFormateur()
    {
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
