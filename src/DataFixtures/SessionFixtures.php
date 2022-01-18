<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Users;
use App\Entity\Comments;
use App\Entity\Dons;
use App\Entity\Sessions;
use PhpParser\Builder\Use_;

class SessionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = \Faker\Factory::create('fr_FR');
        for ($j = 1; $j <= 6; $j++) {
            $user = new Users();
            $user->setLogin($faker->userName())
                ->setPassword($faker->randomElement($array = array('testtest')))
                ->setNom($faker->lastname())
                ->setPrenom($faker->firstName())
                ->setMail($faker->email())
                ->setVille($faker->city())
                ->setTel($faker->randomNumber($nbDigits = NULL, $strict = false))
                ->setDateInscription($faker->dateTimeThisMonth())
                ->setType($faker->randomElement($array = array('Particulier', 'Coach', 'Association')))
                ->setSexe($faker->randomElement($array = array('homme', 'femme')))
                ->setDateNaiss($faker->dateTimeThisMonth())
                ->setNomSociete($faker->company())
                ->setNomAssoc($faker->company());

            $manager->persist($user);

            for ($i = 1; $i <= mt_rand(2, 4); $i++) {
                $session = new Sessions();
                $session->setTitre($faker->word())
                    ->setDescription($faker->sentence())
                    ->setImage($faker->imageUrl())
                    ->setAdresse($faker->address())
                    ->setVille($faker->city())
                    ->setNbMin($faker->numberBetween($min = 2, $max = 5))
                    ->setNbMax($faker->numberBetween($min = 5, $max = 15))
                    ->setType($faker->randomElement($array = array('Entre amis', 'Avec un coach', 'Événements sportifs', 'Événements associatifs')))
                    ->setPrix($faker->numberBetween($min = 0, $max = 100))
                    ->setUser($user)
                    ->setDateStart($faker->dateTimeThisMonth())
                    ->setDateEnd($faker->dateTimeThisMonth());
            }
        }


        $manager->flush();
    }
}
