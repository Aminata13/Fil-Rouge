<?php

namespace App\DataFixtures;

use App\Entity\UserProfils;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture 
{
    private $encode ;

    public function __construct(UserPasswordEncoderInterface $encode)
    {
        $this->encode = $encode ;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i=0; $i < 3; $i++) { 
            $profil = new UserProfils();
            $profil->setLibelle($faker->unique()->randomElement(['Admin', 'Formateur', 'CM']));

            $manager->persist($profil);

            $user = new Users();
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setEmail($faker->email);
            $user->setUsername($faker->unique()->userName);
            $user->setPassword();
            $user->setProfil($profil);

            $manager->persist($user);
        }
        $manager->flush();
    }
}
