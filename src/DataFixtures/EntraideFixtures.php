<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Question;
use Faker\Factory as FakerFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EntraideFixtures extends Fixture implements DependentFixtureInterface
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    
    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create('fr_FR');

        $users = $this->doctrine->getRepository(User::class)->findAll();
    
        // QUESTIONS
        for ($i = 0; $i < 10; $i++) { 
            $question = new Question();
    
            $question->setTitre($faker->words(5, true));
            $question->setContenu($faker->paragraph());
            $question->setImage($faker->imageUrl(640, 480, 'abstract'));
    
            $randomUser = $users[array_rand($users)];
            $question->setUser($randomUser);
    
            $manager->persist($question);
        }
    
        $manager->flush();
    }

    // Cette méthode retourne la liste des fixtures dont celle-ci dépend
    public function getDependencies(): array
    {
        return [
            ProfilFixtures::class, // On dépend de ProfilFixtures
        ];
    }
}
