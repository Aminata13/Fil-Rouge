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
            "tags" => array(1,2,3),
            "niveauCompetences" => array(1,2,3)
        );

        $client = self::createClient();
        $token="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1OTkwNjE0MTcsImV4cCI6MTU5OTA2NTAxNywicm9sZXMiOlsiUk9MRV9BRE1JTiJdLCJ1c2VybmFtZSI6ImFkbWluMSJ9.P8uBw2e4ixYuQQuKPIt6jq2HzmhoqL9YryF6vfe5PHtzOCqsnpYVURoaFKATO7El6sivRpPfnpIkcxpty-ziT60Yc4LD73N-GeFvb62wEe2q_aSfrHfW3yAiBHxZ5Y0ZE0iBSB6hdm8xCSc6bsjBUw1dDhwml8Hx2tO9dq2w472-c6cY3qRBfE5q23o_25uvPkORKpKoEcDiciRe9hCCh4TOzg1dUK2Mp0Yz_UlDvhU_gyU4vBoVz7_QzATrtP8janmoYsKczEUFdi8-0rpYsA5w2D9wXhVQVxqxVO-OrECHV5JNdlsiL_Ypk8JoqB7VTR9dwINMwXh_p40S_ODwgcTUmDW_qyZhCb1HOl-Z6-5HMkiFm8kzF5H0zUfjvkSqb2zKBlnmNxhhfRibmsYxH0l1oxXEtfYMEavRU9S6nJf0V74AlKUPt0jTYLoRunStSZyXdaDA4sPkENFAn8LDv0I78om1162N-_4wUjLSWNuNnKHf2tDqmB3Lh2F-h_ONdHzkBw2OGMVzU72EVh29SprdSo4nyhYqUBpdcKmvLB6MgNiOCc-QYIdPT5rb1Cqs6gnb6X_yXq5ZkuW62S5xxjtBk0Bc8Y5IzBeKY72F92L4mFy9_fKvgTYVOa8DSpHk7AWhztMIsIi3J2AX4u7KDqOuf-6lxwCxJujjj1IE3dY";
        $client->setServerParameter('HTTP_Authorization',$token);

        $client->request('POST', '/api/formateurs/briefs', $data,["image"=>$image]);
        $this->assertEquals(201, $client->getResponse()->getStatusCode());

    }
}