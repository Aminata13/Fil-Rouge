<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EditGroupeController extends AbstractController
{
    /**
     * @Route(
     *     name="edit_groupe",
     *     path="/api/admin/groupes/{id}",
     *     methods={"PUT"}
     * )
     */
    public function index()
    {
        return $this->render('edit_groupe/index.html.twig', [
            'controller_name' => 'EditGroupeController',
        ]);
    }
}
