<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LivrablePartielController extends AbstractController
{
    /**
     * @Route("/livrable/partiel", name="livrable_partiel")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/LivrablePartielController.php',
        ]);
    }
}
