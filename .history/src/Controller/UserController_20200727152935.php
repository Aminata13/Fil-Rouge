<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController 
{
    private $userCreate
    public function addUser(SerializerInterface $serializer, Request $request, ValidatorInterface $validator)
    {
        $regionJson = $request->getContent();
        dd($_POST['username']);
    }
}
