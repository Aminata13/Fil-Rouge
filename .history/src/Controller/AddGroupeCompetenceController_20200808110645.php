<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AddGroupeCompetenceController extends AbstractController
{
    /**
     * @Route("/add/groupe/competence", name="add_groupe_competence")
     */
    public function index()
    {
        return $this->render('add_groupe_competence/index.html.twig', [
            'controller_name' => 'AddGroupeCompetenceController',
        ]);
    }
}
