<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AddPromotionTest extends WebTestCase
{

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
            "groupes"  => array(1,2),
            "langue"  => 1,
            "ressource"  => array("www.oki.com","www.zero.com"),
            "referentiel" => 2,
            "apprenants" => array("abd@gmail.com"),
            "tags" => array(1,2),
            "niveauCompetences" => array(1,2,3)
        );

        $client = self::createClient();
        $token="Bearer ";
        $client->setServerParameter('HTTP_Authorization',$token);

        $client->request('POST', '/api/formateurs/briefs', $data,["image"=>$image]);
        $this->assertEquals(201, $client->getResponse()->getStatusCode());

    }
}