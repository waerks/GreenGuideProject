<?php

namespace App\Command;

use App\Entity\Element;
use App\Entity\Etape;
use App\Entity\TypeElement;
use App\Entity\TypeEtape;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'ImportJSONCommand',
    description: 'Import JSON data into the database',
)]
class ImportJSONCommand extends Command
{
    private $doctrine;
    private $kernel;
    public function __construct(ManagerRegistry $doctrine, KernelInterface $kernel)
    {
        $this->doctrine = $doctrine;
        $this->kernel = $kernel;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $filePath = $this->kernel->getProjectDir() . '/assets/js/components/db_greenguide.json';

        if(!file_exists($filePath)){
            $io->error('Fichier JSON non trouvé.');
            return Command::FAILURE;
        }

        $jsonContent = file_get_contents($filePath);
        $data = json_decode($jsonContent, true);

        if(json_last_error() !== JSON_ERROR_NONE){
            $io->error('Erreur lors du décodage du fichier JSON.');
            return Command::FAILURE;
        }

        $em = $this->doctrine->getManager();

        // Récupérer tous les TypeEtape
        $typeEtapes = $em->getRepository(TypeEtape::class)->findAll();
        $typeEtapeNames = [];

        if (empty($typeEtapes)) {
            $typeEtape1 = new TypeEtape();
            $typeEtape1->setNom('semis');
            $em->persist($typeEtape1);

            $typeEtape2 = new TypeEtape();
            $typeEtape2->setNom('plantation');
            $em->persist($typeEtape2);

            $typeEtape3 = new TypeEtape();
            $typeEtape3->setNom('recolte');
            $em->persist($typeEtape3);

            $em->flush();
        }

        $typeEtapes = $em->getRepository(TypeEtape::class)->findAll();
        foreach ($typeEtapes as $typeEtape) {
            $typeEtapeNames[$typeEtape->getNom()] = $typeEtape;
        }

        // Gérer l'Element
        foreach ($data as $itemData) {
            $element = $em->getRepository(Element::class)->findOneBy(['nom' => $itemData['nom']]);
            
            if(!$element){

                $element = new Element();
                
                // Propriétés de l'Element
                $element->setNom($itemData['nom']);
                $element->setNomScientifique($itemData['nomscientifique']);
                $element->setFamille($itemData['famille']);
                $element->setHauteur($itemData['hauteur']);
                $element->setSol($itemData['sol']);

                // Gérer TypeElement => Catégorie
                $typeElement = $em->getRepository(TypeElement::class)->findOneBy(['nom' => $itemData['categorie']]);
                
                if(!$typeElement){
                    $typeElement = new TypeElement();
                    $typeElement->setNom($itemData['categorie']);

                    $em->persist($typeElement);
                }

                $element->addTypeElement($typeElement);

                $element->setImage($itemData['image']);
                $element->setResume($itemData['resume']);

                // Gérer les Etapes
                foreach ($typeEtapeNames as $type => $typeEtape) {
                    if (isset($itemData[strtolower($type)])) {
                        $etapeData = $itemData[strtolower($type)];

                        // Créer ou récupérer l'étape
                        $etape = new Etape();
                        $etape->setElement($element);
                        $etape->setMois($etapeData['mois']);
                        $etape->setPeriode($etapeData['periode']);
                        $etape->setInstructions($etapeData['instruction']);

                        // Liaison avec TypeEtape
                        $etape->setTypeEtape($typeEtape);

                        $em->persist($etape);
                    }
                }

                $element->setEntretien($itemData['entretien']);
                $element->setRotationDesCultures($itemData['rotationdescultures']);
                $element->setConservation($itemData['conservation']);
                $element->setBenefices($itemData['benefices']);
                $element->setContreIndication($itemData['contreindication']);
                $element->setInformationsNutritionnelles($itemData['informationsnutritionnelles']);

                $em->persist($element);
            }

            // Gérer les plantes ennemies
            if (!empty($itemData['plantesenemies'])) {
                foreach ($itemData['plantesenemies'] as $ennemieNom) {

                    // Récupérer l'élément ennemi
                    $planteEnnemie = $em->getRepository(Element::class)->findOneBy(['nom' => $ennemieNom]);
                    
                    // Vérifier si l'élément ennemi existe
                    if ($planteEnnemie) {
                        // Ajouter la relation d'ennemi
                        $element->addEnnemi($planteEnnemie);
                        $planteEnnemie->addElementEnnemi($element); // Relation bidirectionnelle
                    }
                }
            }
        
            // Gérer les plantes amies
            if (!empty($itemData['plantesamies'])) {
                foreach ($itemData['plantesamies'] as $amieNom) {
                    // Récupérer l'élément ami
                    $planteAmie = $em->getRepository(Element::class)->findOneBy(['nom' => $amieNom]);
                    // Vérifier si l'élément ami existe
                    if ($planteAmie) {
                        // Ajouter la relation d'ami
                        $element->addAmi($planteAmie);
                        $planteAmie->addElementAmi($element); // Relation bidirectionnelle
                    }
                }
            }
        }

        $em->flush();

        $io->success('Les données JSON ont été importées avec succès.');

        return Command::SUCCESS;
    }
}