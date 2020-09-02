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
        $token="Bearer ";
        $client->setServerParameter('HTTP_Authorization',$token);
        $client->request('GET','/api/admin/profils');
        $this->assertResponseIsSuccessful();;
    }

}