<?php

namespace App\DataFixtures;

use App\Entity\UserProfil;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture 
{
<<<<<<< HEAD
    private $encode ;

    public function __construct(UserPasswordEncoderInterface $encode)
    {
        $this->encode = $encode ;
    }
=======
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

>>>>>>> 6cd82f05b8f819d0ccbb116116ed12f4a8c395ad
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $profils = ["ADMIN", "FORMATEUR", "APPRENANT", "CM"];
        foreach ($profils as $key => $libelle) {
            $profil = new UserProfil();
            $profil->setLibelle($libelle);
            $manager->persist($profil);

            $user = new Users();
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setEmail($faker->email);
            $user->setUsername($faker->unique()->userName);
            $user->setPassword($this->encode->encodePassword($user,"password"));
            $user->setProfil($profil);

            $manager->persist($user);
            $manager->flush();
            
            for ($i = 1; $i <= 3; $i++) {
                $user = new User();
                $user->setProfil($profil);
                $user->setUsername(strtolower($libelle) . $i);
                //Génération des Users
                $password = $this->encoder->encodePassword($user, 'password');
                $user->setPassword($password);
                $user->setLastname($faker->lastName);
                $user->setFirstname($faker->firstName);
                $user->setEmail($faker->email);
                
                $manager->persist($user);
            }
            $manager->flush();
        }
    }
}
