<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AddPromotionTest extends WebTestCase
{


    public function testpostPromotion()
    {
        $image = new UploadedFile(
            'C:\Users\DELL\Pictures\nodeJs.png',
            'nodeJs.png',
            'image/png',
            3,62 ,
            UPLOAD_ERR_OK,
            true
        );

        $data = array
        (
            "titre"  => "Promotion 2021-image",
            "description"  => "Futuriste",
            "lieu" => "Dakar",
            "referenceAgate"  => "4JKH56DBK",
            "dateFin"  => "2022-08-21",
            "langue"  => "Francais",
            "fabrique"  => "ODC",
            "referentiels" => array("Dev web et mobile"),
            "apprenants" => array("abd@gmail.com"),
            "formateurs" => array("1"),
        );

        $client = self::createClient();
        $token="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1OTkwMDcwNzUsImV4cCI6MTU5OTAxMDY3NSwicm9sZXMiOlsiUk9MRV9BRE1JTiJdLCJ1c2VybmFtZSI6ImFkbWluMSJ9.D-3OP0233X3G0BiqcTnmNx930spq94nBVD8T0iQZSIvTvKQ2Yg_U718xbk6Ap1k41nMBA5eApgosnJgwxrgAHrqFcD7fDyhXmwrAx7ZfGAT1U-PiE_JHJwOIuxtXOvXmZ8VrH2mpRLABrp8tvZZvMIudCunQp-nik27Y9fv2GFWkL-cTBi8AtTSB_FPCm3tgGWsw9A-NlZnIRkxj2_04GoaFuhRVZK1p8NfjIv9Z71NMnNobh2svBNcNulPQGeEB4nhRS3u92DdKgYll5kzknzwWtOmBerpdSYMu5YE371mrMfM7x3NNTQMeBGCzcXAvFKwICq83IyFZfA3IxVL_EnFM8NmiWioVJiO2-wU85pc0jOnU4JB78PHs-lgQPNLzC2_qxhXvWKcigGzNagnGc3ImnUvbXM_1siLMb8KPzSWlSB5HNR6EG98g3LvWCa6MMoV62tgsBX8tQAf45a9zWEJhdFd4XEVQt5wnXT7XWkSfFL6uUjw-0CoGDL1d81YjV7APxQ5SfUyQPe3Fir8zXjVQDpBsKGRLy-byPPsvH-y1zCkWbNhrTYbfQhOS-osHSkoQdiurCOpyFk8FBhWDJtRMvaUx-WnwlutOyTEcoFG-z6M-rsD1YtbuWwD8jKkHxd6RwCeRFlqm1gC2GeRPv0sQzFBnB1fJy9DCXsBX57g";
        $client->setServerParameter('HTTP_Authorization',$token);

        $client->request('POST', '/api/admin/promotion', $data,["image"=>$image]);
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    public function testpostBrief()
    {

        $image = new UploadedFile(
            'C:\Users\DELL\Pictures\nodeJs.png',
            'nodeJs.png',
            'image/png',
            3,62 ,
            UPLOAD_ERR_OK,
            true
        );

        $ressourceTab = new UploadedFile(
            'C:\Users\DELL\Pictures\nodeJs.png',
            'nodeJs.png',
            'image/png',
            3,62 ,
            UPLOAD_ERR_OK,
            true
        );

        

        $data = array
        (
            "titre"  => "Promotion 2021-image",
            "description"  => "Futuriste",
            "lieu" => "Dakar",
            "referenceAgate"  => "4JKH56DBK",
            "dateFin"  => "2022-08-21",
            "langue"  => 1,
            "ressource"  => array("www.oki"),
            "referentiel" => 1,
            "apprenants" => array("abd@gmail.com"),
            "tags" => array(1,2,3),
            "niveauCompetences" => array(1,2,3)
        );

        $client = self::createClient();
        $token="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1OTkwMDcwNzUsImV4cCI6MTU5OTAxMDY3NSwicm9sZXMiOlsiUk9MRV9BRE1JTiJdLCJ1c2VybmFtZSI6ImFkbWluMSJ9.D-3OP0233X3G0BiqcTnmNx930spq94nBVD8T0iQZSIvTvKQ2Yg_U718xbk6Ap1k41nMBA5eApgosnJgwxrgAHrqFcD7fDyhXmwrAx7ZfGAT1U-PiE_JHJwOIuxtXOvXmZ8VrH2mpRLABrp8tvZZvMIudCunQp-nik27Y9fv2GFWkL-cTBi8AtTSB_FPCm3tgGWsw9A-NlZnIRkxj2_04GoaFuhRVZK1p8NfjIv9Z71NMnNobh2svBNcNulPQGeEB4nhRS3u92DdKgYll5kzknzwWtOmBerpdSYMu5YE371mrMfM7x3NNTQMeBGCzcXAvFKwICq83IyFZfA3IxVL_EnFM8NmiWioVJiO2-wU85pc0jOnU4JB78PHs-lgQPNLzC2_qxhXvWKcigGzNagnGc3ImnUvbXM_1siLMb8KPzSWlSB5HNR6EG98g3LvWCa6MMoV62tgsBX8tQAf45a9zWEJhdFd4XEVQt5wnXT7XWkSfFL6uUjw-0CoGDL1d81YjV7APxQ5SfUyQPe3Fir8zXjVQDpBsKGRLy-byPPsvH-y1zCkWbNhrTYbfQhOS-osHSkoQdiurCOpyFk8FBhWDJtRMvaUx-WnwlutOyTEcoFG-z6M-rsD1YtbuWwD8jKkHxd6RwCeRFlqm1gC2GeRPv0sQzFBnB1fJy9DCXsBX57g";
        $client->setServerParameter('HTTP_Authorization',$token);

        $client->request('POST', '/api/admin/promotion', $data,["image"=>$image]);
        $this->assertEquals(201, $client->getResponse()->getStatusCode());

    }
}