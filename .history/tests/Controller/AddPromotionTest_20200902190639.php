<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AddPromotionTest extends WebTestCase
{

    protected function createAuthenticatedClient(string $login, string $password): KernelBrowser
    {
        $client = static::createClient();
        $infos=["username"=>$login,
               "password"=>$password];
        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($infos)
        );
        $this->assertResponsestatusCodeSame(Response::HTTP_OK);
        $data = \json_decode($client->getResponse()->getContent(), true);
        $client->setServerParameter('HTTP_Authorization', \sprintf('Bearer %s', $data['token']));
        $client->setServerParameter('CONTENT_TYPE', 'application/json');

        return $client;
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
            "titre"  => "titre brief",
            "description"  => "description du brief",
            "livrableAttendus" => array("Git","trello"),
            "contexte"  => "context brief",
            "modalitePedagogique" => "modalite peda du brief",
            "criterePerformance" => "critaire du brief",
            "modaliteEvaluation" => "modalite du brief",
            "livrables" => "Trello git figma",
            "groupes"  => array(1),
            "langue"  => 1,
            "ressource"  => array("www.oki.com","www.zero.com"),
            "referentiel" => 2,
            "apprenants" => array("abd@gmail.com"),
            "tags" => array(1,2),
            "niveauCompetences" => array(1,2,3)
        );

        $client = $this->createAuthenticatedClient("formateur1","password");

        $client->request('POST', '/api/formateurs/briefs', $data,["image"=>$image]);
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }


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
            "titre"  => "Promotion 2021-image-1",
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

        $client = $this->createAuthenticatedClient("admin1","password");

        $client->request('POST', '/api/admin/promotion', $data,["image"=>$image]);
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }
}