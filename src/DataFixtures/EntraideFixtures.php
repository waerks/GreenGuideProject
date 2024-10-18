<?php

namespace App\DataFixtures;

use App\Entity\Commentaire;
use App\Entity\User;
use App\Entity\Question;
use Faker\Factory as FakerFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EntraideFixtures extends Fixture implements DependentFixtureInterface
{
    private $slugger;
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine, SluggerInterface $slugger)
    {
        $this->doctrine = $doctrine;
        $this->slugger = $slugger;
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

            // Générer une image aléatoire ou null
            if ($faker->boolean(50)) { // 50% de chance d'avoir une image
                $question->setImage($faker->imageUrl(640, 480, 'abstract'));
            } else {
                $question->setImage(null); // Pas d'image
            }
    
            $randomUser = $users[array_rand($users)];
            $question->setUser($randomUser);
    
            $question->generateSlug($this->slugger);
        
            $manager->persist($question);
        }
        
        $manager->flush(); // Persist questions first
    }

    // Cette méthode retourne la liste des fixtures dont celle-ci dépend
    public function getDependencies(): array
    {
        return [
            ProfilFixtures::class, // On dépend de ProfilFixtures
        ];
    }
}
