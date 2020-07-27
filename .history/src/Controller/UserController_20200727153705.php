<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController //extends AbstractController
{
    private $userCreateHandler ;

    public function __construct(UserCreateHandler $userCreateHandler)
    {
        $this->userCreateHandler = $userCreateHandler ;
    }

    public function __invoke(User $data):User
    {
        $this->userCreateHandler->handle();

        return $data ;
    }

    /**
    * @Route(
    *   name="add_user",
    *   path="api/users",
    *   methods={"POST"},
    *   defaults={
    *      "_controller"="\app\ControllerUserController::addUser",
    *      "_api_resource_class"=User::class,
    *      "_api_collection_operation_name"="add_user"
    *   }
    * )
     
    public function addUser(SerializerInterface $serializer, Request $request, ValidatorInterface $validator)
    {
        $regionJson = $request->getContent();
        dd($_POST['username']);
    }
    */
}
