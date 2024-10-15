<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Recette;
use Faker\Factory as FakerFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\FixturesBundle\Fixture;

class RecetteFixtures extends Fixture
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

        for ($i=0; $i < 10; $i++) { 
            $recette = new Recette;

            $recette->setNom($faker->words(5, true));
            $recette->setConseil($faker->paragraph());
            $recette->setNombreDePersonne($faker->numberBetween(1, 20));

            $ingredients = [];
            for ($j=0; $j < 5; $j++) { 
                $ingredients[] = $faker->word();
            }
            $recette->setIngredients($ingredients);

            $recette->setTempsDePreparation($faker->numberBetween(1, 200));
            $recette->setTempsDeCuisson($faker->numberBetween(1, 200));

            $etapes = [];
            for ($k=0; $k < 5; $k++) { 
                $etapes[] = $faker->word();
            }
            $recette->setEtapes($etapes);

            $recette->setImage($faker->imageUrl(640, 480, 'abstract'));

            $randomUser = $users[array_rand($users)];
            $recette->setUser($randomUser);

            $manager->persist($recette);
        }

        $manager->flush();
    }
}
