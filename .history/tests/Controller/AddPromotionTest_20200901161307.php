<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AddPromotionTest extends WebTestCase
{
    private    ?KernelBrowser $client = null;
    protected  ?KernelBrowser $admin = null;
    protected  ?KernelBrowser $formateur = null;

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {

        if (null === $this->client) {
            $this->client = static::createClient();
        }

        if (null === $this->admin) {
            $this->admin = clone $this->client;
            $this->admin = $this->createAuthenticatedClient($this->admin, 'admin1', 'password');
        }

        if (null === $this->formateur) {
            $this->formateur = clone $this->client;

            $this->formateur =  $this->createAuthenticatedClient($this->formateur, 'formateur1', 'password');
        }
    }

    protected function createAuthenticatedClient(KernelBrowser &$client, string $username, string $password)
    {
        $client->request(
            'POST',
            '/api/login_check',
            [
                '_username' => $username,
                '_password' => $password,
            ]
        );

        $data = json_decode($client->getResponse()->getContent(), true);
         dd($data);
        $client->setServerParameter('HTTP_Authorization', \sprintf('Bearer %s', $data['token']));
        $client->setServerParameter('CONTENT_TYPE', 'application/json');

        return $client;
    }

    pr


    
    /** @test */
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

        $this->setUp();
        //dd($this->admin);

        // $userRepository = static::$container->get(UserRepository::class);
        // $user = $userRepository->find(1);

        // $client->loginUser($user);
        // dd($user);

        // $client->request('POST', '/api/admin/promotion', $data, ['image' => $image]);

        // $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    public function postReferentiel()
    {
        $data = array
        (
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