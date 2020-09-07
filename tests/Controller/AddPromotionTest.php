<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AddPromotionTest extends WebTestCase
{
    protected function createAuthenticatedClient($username = 'admin1', $password = 'password')
    {
        $client = static::createClient();
        
        $test = $client->request(
            'POST',
            '/api/login_check',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode(array(
                '_username' => $username,
                '_password' => $password,
            ))
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        dd($data['token']);

        // $client = static::createClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    /** @test */
    public function postPromotion()
    {

        $image = new UploadedFile(
            'C:\Users\AMINA\Documents\Sonatel Academy\languages2019.png',
            'languages2019.png',
            'image/png',
            null
        );

        $data = array(
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
        $client = $this->createAuthenticatedClient();

        // $userRepository = static::$container->get(UserRepository::class);
        // $user = $userRepository->find(1);

        // $client->loginUser($user);
        // dd($user);

        $client->request('POST', '/api/admin/promotion', $data, ['image' => $image]);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }


    public function postReferentiel()
    {
        $data = array(
            "libelle"  => "Referentiel test",
            "description"  => "Referentiel test description",
            "critereAdmissions" => array("Admission1"),
            "critereEvaluations" => array("Evaluation1"),
            "groupeCompetences" => array("GroupeComp1", "GroupeComp2"),
            "programme" => null,
        );
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->find(1);

        $client->loginUser($user);

        $client->request('POST', '/api/admin/referentiels', $data);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }
}
