<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AddPromotionTest extends WebTestCase
{

    public function testUri()
    {
        $client = self::createClient();
        $token="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1OTg5OTc4MDMsImV4cCI6MTU5OTAwMTQwMywicm9sZXMiOlsiUk9MRV9BRE1JTiJdLCJ1c2VybmFtZSI6ImFkbWluMSJ9.nuiZKx3ybt0a-BhqemqRsmNOFVO1Ug5FSidOylnLYAje_3nvVulktbjlB6jJLskw_-s26II1uJVb1Hgw8669Uhx9MBrCmLoIWqWZCP_rifgKjaP0P1CiMhMgyJbD8tH3MhJmXJaMaQ9DN5gfxBNTTzI3QO0k1ailI0K2xZrasuW87TrD61uIdxrMp3NuWvjOXqRzcNuNiwSqz6hSjMgpEes5bGSE4UM5d9UE9qlPTBDOGz0zrB7G6kpawKbNKlEtBBa0TTLMw9Hkh6ev3d1enqXv8gfaFRNv9fIdyO6rnafNPbEME17Iiv7hssQbqb-bJJiL0AUIhHuQDoaLAnr5F-bX0ANLbbcpOMw9DYD1uv2XnJoMtt_jkejYOHjdchHEo5qsdHUdn_F0bTJtzBq8i8jT1pke8vfW4axhrFxFAZIvaXnUodfEBtPo8-CpEFtdUSE7SGWf0CHyO7HChheGNMQaQ155t9rkD0h8ZDiqkafuWtz3AFkaM6Bd2LmXMuOksewG2go-gsG5bHVO78t28stLQyuyQ9K2iv_JiGgDZdBdqoXeNwoLhYqDB-5Y9jXprGgT25TW1w8NvY5fi91XB94uHmzbPtIgDcoYeBcgyjiwp7my5nPPJcGQa-LVEU_IHPHu2DR7iWmucNQlWmyia1AeDctoRN1bISrmC2YSEhI";
        $client->setServerParameter('HTTP_Authorization',$token);
        $client->request('GET','/api/admin/profils');
        $this->assertResponseIsSuccessful();;
    }

}