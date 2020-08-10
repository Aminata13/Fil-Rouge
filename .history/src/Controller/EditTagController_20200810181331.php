<?php

namespace App\Controller;

use App\Repository\TagRepository;
use App\Repository\GroupeTagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditTagController extends AbstractController
{
    /**
     * @Route(
     *     name="edit_tag",
     *     path="/api/admin/tags/{id}",
     *     methods={"PUT"}
     * )
     */
    public function editTag(int $id, GroupeTagRepository $repoGroupeTag, TagRepository $repoTag, EntityManagerInterface $em, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $tag = $repoTag->find($id);
        if(is_null($tag)) {
            return new JsonResponse("Ce groupe de tag n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
        }

        /**Archivage */
        if(isset($data['deleted']) && $data['deleted']) {
            $tag->setDeleted(true);
            return new JsonResponse('Groupe de tags archivé.', Response::HTTP_NO_CONTENT, [], true);
        }

        if (empty($data['libelle'])) {
            return new JsonResponse('Le libelle est requis.', Response::HTTP_BAD_REQUEST, [], true);
        }

        
        $tag->setLibelle($data['libelle']);
        
        foreach ($tag->getGroupeTags() as $value) {
            $tag->removeGroupeTag($value);
        }

        for ($i = 0; $i < count($data["groupeTags"]); $i++) {
            $grpTag = $repoGroupeTag->findBy(array('libelle' => $data["groupeTags"][$i]["libelle"]));
            if (!is_null($grpTag)) {
                $tag->addGroupeTag($grpTag[0]);
            }
        }

        if (count($tag->getGroupeTags()) < 1) {
            return new JsonResponse("Veuillez renseigner au moins un groupe de tags existant.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($tag);
        $em->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
}
