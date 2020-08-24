<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Entity\MessageChat;
use App\Entity\FilDiscussion;
use App\Repository\ApprenantRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProfilSortieRepository;

use App\Repository\FilDiscussionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/api")
 */
class ChatController extends AbstractController
{
    /**
     * @Route("users/promotions/{id_promo}/apprenants/{id_apprenant}/chats", name="show_messages_apprenant")
     */
    public function showMessagesByApprenant(int $id_promo, int $id_apprenant, PromotionRepository $repoPromo, ApprenantRepository $repoApprenant, FilDiscussionRepository $repoDiscussion)
    {
        $promo = $repoPromo->find($id_promo);
        if (is_null($promo)) {
            return new JsonResponse("Cette promotion n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }
        $apprenant = $repoApprenant->find($id_apprenant);
        if (is_null($apprenant)) {
            return new JsonResponse("Cet apprenant n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }
        $filDiscussion = $repoDiscussion->findBy(array('promotion_id' => $id_promo));
        if (empty($filDiscussion)) {
            return new JsonResponse("Fil de discussion vide.", Response::HTTP_NOT_FOUND, [], true);
        }
        if (!$promo->getApprenants()->contains($apprenant)) {

            return new JsonResponse("Cet apprenant n'existe pas dans la promotion.", Response::HTTP_NOT_FOUND, [], true);
        }
        $commentaires = array();

        $currentDate = date('d-m-y');
        foreach ($apprenant->getUser()->getMessageChats() as $value) {
            if ($value->getDate() >= $currentDate) {
                $commentaires[] = $value;
            }
        }
        if (empty($commentaires)) {
            return new JsonResponse("Il n'y a pas de commentaires aujourd'hui.", Response::HTTP_NOT_FOUND, [], true);
        }
        $commentairesJson = $this->serializer->serialize($commentaires, 'json', ["groups" => ["chat:read"]]);
        return new JsonResponse($commentairesJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/users/promotions/{id_promo}/apprenants/{id_apprenant}/chats", name="add_messages_apprenant")
     */
    public function addMessagesByApprenant(EntityManagerInterface $em, SerializerInterface $serializer, Request $request, int $id_promo, int $id_apprenant, PromotionRepository $repoPromo, ApprenantRepository $repoApprenant, FilDiscussionRepository $repoDiscussion)
    {
        $commentaireTab = $request->request->all();
        $commentaire = $serializer->denormalize($commentaireTab, MessageChat::class, true, ["groups" => "commentaire:write"]);
        $commentaire->setDate(new \DateTime());

        $promo = $repoPromo->find($id_promo);
        if (is_null($promo)) {
            return new JsonResponse("Cette promotion n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }

        $apprenant = $repoApprenant->find($id_apprenant);
        if (is_null($apprenant)) {
            return new JsonResponse("Cet apprenant n'existe pas.", Response::HTTP_NOT_FOUND, [], true);
        }


        $filDiscussion = $repoDiscussion->findBy(array('promotion' => $id_promo));
        //dd($filDiscussion);
        if (empty($filDiscussion)) {
            $filDiscussion = new FilDiscussion();
            $filDiscussion->setTitre("discussion promo courant " . date('y'));
            $filDiscussion->setDate(new \DateTime());
            $filDiscussion->setPromotion($promo);
            
        }
        if (!$promo->getApprenants()->contains($apprenant)) {
            return new JsonResponse("Cet apprenant n'existe pas dans la promotion.", Response::HTTP_NOT_FOUND, [], true);
        }
        $commentaire->setUser($apprenant->getUser());

        if (isset($filDiscussion[0])) {
            $filDiscussion[0]->addMessageChat($commentaire);
            $em->persist($filDiscussion[0]);
        }else{
            $filDiscussion->addMessageChat($commentaire);
            $em->persist($filDiscussion);
        }
       
        
        $em->flush();
        return new JsonResponse("Success", Response::HTTP_OK, [], true);
    }
}
