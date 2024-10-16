<?php

namespace App\DataFixtures;

use App\Entity\User;
use Faker\Factory as FakerFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfilFixtures extends Fixture
{
    private $passwordHasher;
    private $slugger;

    public function __construct(UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger)
    {
        $this->passwordHasher = $passwordHasher;
        $this->slugger = $slugger;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create('fr_FR');

        for ($i=0; $i < 10; $i++) { 
            $user = new User();
            
            $user->setEmail($faker->email);
            $user->setPassword($this->passwordHasher->hashPassword($user,'password123'));
            $user->setNom($faker->lastName);
            $user->setPrenom($faker->firstName);
            $user->setPseudo($faker->userName);
            $user->setAvatar($faker->imageUrl(150, 150, 'people', true));

            $user->setRoles(['ROLE_USER']);

            $user->generateSlug($this->slugger);

            $manager->persist($user);
        }


        $manager->flush();
    }
}
