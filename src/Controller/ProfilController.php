<?php

namespace App\Controller;

use App\Entity\Recette;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    private $doctrine;
    private $slugger;

    public function __construct(ManagerRegistry $doctrine, SluggerInterface $slugger)
    {
        $this->doctrine = $doctrine;
        $this->slugger = $slugger;
    }

    #[Route('/profil', name: 'profil')]
    public function index(): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Récupérer les recettes de cet utilisateur
        $recettes = $this->doctrine->getRepository(Recette::class)->findBy(['user' => $user]);

        return $this->render('profil/profil.html.twig', [
            'recettes' => $recettes,
        ]);
    }
}
