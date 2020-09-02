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
            '/api/login',
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

        $client = $this->createAuthenticatedClient("formateur1",);
        // $token="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1OTkwNjIxNDIsImV4cCI6MTU5OTA2NTc0Miwicm9sZXMiOlsiUk9MRV9GT1JNQVRFVVIiXSwidXNlcm5hbWUiOiJmb3JtYXRldXIxIn0.keE9OpTS-1Z-tTbrkxoFDpuL4TcUWGVx29JlvSLabWQTySzdM3kpqe1zYcy5PBdAkz8Han0EAL7YU4S4elpRFGeUmi20zcjwy2ePlpXfdqDzryAQMbpDselJaRMGEsAuEurg7wgzSycbFlmiA12KIeXwFTeKgNVTQz4PltCaptkb5H1_MYxTfZgQzN78BKUs8mw7w62bB_0XjVvCMCgI0c-apK9GF_OD81NwPp1Q6T6x2k5WzK50E9GkHPaJ0vM7ba-_IO-tx-Ef7GfE0xAWVE4-fltMAE1hNh8VtMhHauSHkAI2zB7x4FRibHDSYiZ3Gfw8k4JN9ajn5b8iFP7uP39JNGayMtCff5bH3Oy7jwpF_y2n0_JBL1v0RZzEUKocEQNr5YHH196pdEzlUzOlPmcoVvO3cFGGupX7cTWA5RTcdSBUCCNUuJL93EgVfEzEcGlgt3YIC6q1YEzhkFj5pQjRyYtSNb2_FbXkFxXJ0-4kweaK8vm5JS8E96p4wnopzRPgzs3pMQjNQ7CT_UhZzkQC9x_KnOtdgecZeRnPKHHBMVb4L3orzpFcThq2spJWmS2Q41ukjInYtN7129-AQvBZOn3C0Dsttw21WffOwnpBP1PqHGTgBqvc2R3Xsq1eYTKjTbkGXdagxWMihpx1IVxmrtb1XXmtSUyyNDvH11E";
        // $client->setServerParameter('HTTP_Authorization',$token);

        $client->request('POST', '/api/formateurs/briefs', $data,["image"=>$image]);
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }
}