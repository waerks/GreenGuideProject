<?php

namespace App\DataFixtures;

use App\Entity\Element;
use App\Entity\Etape;
use App\Entity\TypeElement;
use App\Entity\TypeEtape;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;

class RepertoireFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create('fr_FR');

        // Créer un tableau pour stocker les éléments créés
        $elements = [];

        for ($i = 0; $i < 10; $i++) {
            $element = new Element();

            // Remplir les propriétés de $element
            $element->setNom($faker->word());
            $element->setNomScientifique($faker->word());
            $element->setFamille($faker->word());
            $element->setHauteur($faker->numberBetween(10, 200) . ' cm');
            $element->setImage($faker->imageUrl(640, 480, 'abstract'));
            $element->setSol($faker->sentence());
            $element->setResume($faker->paragraph());
            $element->setEntretien($faker->paragraph());
            $element->setRotationDesCultures($faker->paragraph());
            $element->setConservation($faker->paragraph());
            $element->setContreIndication($faker->paragraph());
            $element->setInformationsNutritionnelles($faker->paragraph());
            $element->setBenefices($faker->paragraph());

            // Persister l'élément
            $manager->persist($element);
            // Ajouter l'élément à la collection
            $elements[] = $element;

            // Définir et persister un type d'élément
            $typeElement = new TypeElement();
            $typeElement->setNom($faker->randomElement(['fruit', 'legume', 'plante']));
            $manager->persist($typeElement);
            $element->addTypeElement($typeElement);

            // Créer et associer des étapes
            $moisPossibles = ['janvier', 'fevrier', 'mars', 'avril', 'mai', 'juin', 'juillet', 'aout', 'septembre', 'octobre', 'novembre', 'decembre'];

            foreach (['semis', 'plantation', 'recolte'] as $type) {
                // Créer une nouvelle entité Etape
                $etape = new Etape();
                $etape->setMois([$faker->randomElement($moisPossibles)]);
                $etape->setPeriode($faker->sentence());
                $etape->setInstructions($faker->paragraph());

                // Créer et associer le type d'étape correspondant
                $etapeType = new TypeEtape();
                $etapeType->setNom($type);
                $manager->persist($etapeType);

                $etape->setTypeEtape($etapeType); // Relier l'étape à son type
                $etape->setElement($element); // Relier l'étape à l'élément

                $manager->persist($etape);
                $element->addEtape($etape); // Ajouter l'étape à l'élément
            }
        }

        // Établir les relations entre amis et ennemis après que tous les éléments ont été créés
        foreach ($elements as $element) {
            // Choisir aléatoirement un ami et un ennemi parmi les autres éléments
            $ami = $faker->randomElement($elements);
            $ennemi = $faker->randomElement($elements);

            // S'assurer que l'ami et l'ennemi ne sont pas le même élément
            if ($ami !== $element) {
                $element->addAmi($ami);
            }

            if ($ennemi !== $element) {
                $element->addEnnemi($ennemi);
            }
        }

        // Finaliser la persistance
        $manager->flush();
    }
}