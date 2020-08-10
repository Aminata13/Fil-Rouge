<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Repository\GroupeTagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditGroupeTagController extends AbstractController
{
    /**
     * @Route(
     *     name="edit_groupe_tag",
     *     path="/api/admin/groupe_tags/{id}",
     *     methods={"PUT"}
     * )
     */
    public function editGroupeTag(int $id, GroupeTagRepository $repoGroupeTag, TagRepository $repoTag, EntityManagerInterface $em, Request $request)
    {
        $data=json_decode($request->getContent(),true);

        $groupeTag = $repoGroupeTag->find($id);
        if(is_null($groupeTag)) {
            return new JsonResponse("Ce groupe de compétences n'existe pas.", Response::HTTP_BAD_REQUEST, [], true);
        }

        /**Archivage */
        if(isset($data['deleted']) && $data['deleted']) {
            $groupeTag->setDeleted(true);
            return new JsonResponse('Groupe de Compétences archivé.', Response::HTTP_NO_CONTENT, [], true);
        }
        
        if (empty($data['libelle'])) {
            return new JsonResponse('Le libelle est requis.', Response::HTTP_BAD_REQUEST, [], true);
        }

        $tags = $data['tags'];
        if (count($tags) < 1) {
            return new JsonResponse("Un tag est requis.", Response::HTTP_BAD_REQUEST, [], true);
        }
        
        
        $tabTags = $groupeTag->getTags();
        
        foreach ($tabTags as $value) {
            $groupeTag->removeTag($value);
        }

        $groupeTag->setLibelle($data['libelle']);
        
        $tabLibelle = [];
        foreach ($data['tags'] as $value){ 
            if (!empty($value['libelle'])){
                $tag = $repoTag->findBy(array('libelle' => $value['libelle']));
                if ($tag) {
                    $groupeTag->addTag($tag[0]);
                } else {
                    if (!in_array($value['libelle'], $tabLibelle)) {
                        $tabLibelle[] = $value['libelle'];
                        $tag = new Tag();
                        $tag->setLibelle($value['libelle']);
                        $groupeTag->addTag($tag);
                    }
                }
            }
        }

        if (count($groupeTag->getTags())<1) {
            return new JsonResponse("Les libellés des tags sont requis.", Response::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($groupeTag);
        $em->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
}
