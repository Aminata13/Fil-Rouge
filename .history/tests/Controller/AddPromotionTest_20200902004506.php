<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AddPromotionTest extends WebTestCase
{
    

    public function postPromotion()
    {
        $image = new UploadedFile(
            'C:\Users\DELL\Pictures\nodeJs.png',
            'nodeJs.png',
            'image/png',
            null
        );

        $data = array
        (
            "titre"  => "Promotion 2021",
            "description"  => "Futuriste",
            "lieu" => "Dakar",
            "referenceAgate"  => "4JKH56DBK",
            "dateFin"  => "2022-08-21",
            "langue"  => "Français",
            "fabrique"  => "ODC",
            "referentiels" => array("Développement Web et Mobile"),
            "apprenants" => array("aminata.ba@univ-thies.sn"),
            "formateurs" => array("1"),
        );

        $client = self::createClient();
        $token="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1OTg5OTkxNzcsImV4cCI6MTU5OTAwMjc3Nywicm9sZXMiOlsiUk9MRV9BRE1JTiJdLCJ1c2VybmFtZSI6ImFkbWluMSJ9.iZ761ErD6wmNlBBTTQ93PS7eSS0gMll7VSeUO84W80HwDf5FsSyaPMjB75IssCZeyBBvPfV6MtHy0kAKgEaF2nn8jmTtKbaPj20J9531917uWREi7NnJueISc0xWBXdJuOzKOHIBxT8WyKx7dXt0EIwfWzNTHo-o1FqMrJsdk9xMm92wfRgs-VEufFWYBeTcgfcQbXrA11xHqLgwF394K4LmtHAWhFmZ78xwiNx7bUXDQ4hUg58XQEha7C-DQL52HQHljJqxv1JzhsYckxz-_su9Ebwdy3pp6_doVnfw2Gs1KMdzyssVJ-Sy2rM8O3VuoUofpuLagv2bvESmZFlmx5GhpJ0lN-5maAxyi67kn3gUJfG1T_11o5IvHux0W8E3TOVw26xYAYL5x2DEXWvqz_otccC9LheM4v8T0ATtzY3ehzUoC3fOdt9o6rFuO4_nXb3YGDwm6UJeNzv4JApKlhVWLEN2sn1L_dfZvobXtK9kKlZLWUxa0XrqDAmfFsfqVLAXypcTym-T36I6eMpt4EgUw25nJa16CC_TVK1QuTTGfMJAU5EL9Er1ufQtZ_zV3Acw6gstG6_OmjGE2mNxK1TYdhVH5h6di8HwapwfsHXFsFKhuXC57uX0IY9SvzTEWsBAXDMFrNVZrykCH0WzsK9bTXFRO0dOi-HrpjXFmVE";
        $client->setServerParameter('HTTP_Authorization',$token);

        $client->request('POST', '/api/admin/promotion', $data,['image' => $image]);
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

}