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

class CommentaireFixtures extends Fixture implements DependentFixtureInterface
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
    
        // Récupération des utilisateurs et des questions
        $users = $this->doctrine->getRepository(User::class)->findAll();
        $questions = $this->doctrine->getRepository(Question::class)->findAll();

        // Vérifier que la liste des questions n'est pas vide
        if (empty($questions)) {
            throw new \Exception('No questions found in the database. Please load QuestionFixtures first.');
        }

        $commentaires = []; // Liste pour stocker les commentaires créés

        // COMMENTAIRES
        for ($i = 0; $i < 30; $i++) { 
            $commentaire = new Commentaire();
    
            $commentaire->setTitre($faker->words(3, true));
            $commentaire->setContenu($faker->paragraph());
            $commentaire->setImage($faker->imageUrl(640, 480, 'people'));

            // Attribution aléatoire d'un utilisateur
            $randomUser = $users[array_rand($users)];
            $commentaire->setUser($randomUser);

            // Attribution aléatoire d'une question
            $randomQuestion = $questions[array_rand($questions)];
            $commentaire->setQuestion($randomQuestion);

            // Génération du slug basé sur le titre
            $commentaire->generateSlug($this->slugger);

            // Attribution aléatoire d'un commentaire parent
            if ($i > 0 && $faker->boolean(30)) { // 30% de chance d'avoir un parent
                $randomParentComment = $commentaires[array_rand($commentaires)];
                $commentaire->setCommentaireParent($randomParentComment);

                // Assigner la même question que le parent
                $commentaire->setQuestion($randomParentComment->getQuestion());
            }
        
            $manager->persist($commentaire);
            $commentaires[] = $commentaire; // Ajouter le commentaire à la liste
        }

        // Générer des réponses pour certains commentaires
        foreach ($commentaires as $commentaire) {
            if ($faker->boolean(60)) { // 60% de chance d'avoir des réponses
                for ($j = 0; $j < rand(1, 3); $j++) {
                    $reponse = new Commentaire();
                    $reponse->setTitre($faker->sentence());
                    $reponse->setContenu($faker->paragraph());
                    $reponse->setImage($faker->optional()->imageUrl(640, 480, 'abstract'));
        
                    // Associer un utilisateur aléatoire
                    $randomUser = $users[array_rand($users)];
                    $reponse->setUser($randomUser);
        
                    // Assigner un commentaire parent aléatoire pour cette réponse
                    $parentComment = $faker->randomElement($commentaires);
        
                    // Pour éviter de lier une réponse à elle-même
                    if ($reponse !== $parentComment) {
                        $reponse->setCommentaireParent($parentComment);
                    }
        
                    // Assigner la même question que le parent
                    $reponse->setQuestion($commentaire->getQuestion());
        
                    // Générer le slug pour la réponse
                    $reponse->generateSlug($this->slugger);
        
                    $manager->persist($reponse);
                    $commentaires[] = $reponse; // Ajouter la réponse à la liste
                }
            }
        }
        
        $manager->flush(); // Persist all comments
    }

    // Cette méthode retourne la liste des fixtures dont celle-ci dépend
    public function getDependencies(): array
    {
        return [
            EntraideFixtures::class,
            ProfilFixtures::class,
        ];
    }
}