<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecetteController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    
    #[Route('/recette', name: 'recette')]
    public function index(): Response
    {
        $recettes = $this->doctrine->getRepository(Recette::class)->findAll();
        $users = $this->doctrine->getRepository(User::class)->findAll();

        $vars = [
            'recettes' => $recettes,
            'users' => $users
        ];

        return $this->render('recette/recette.html.twig', $vars);
    }
    #[Route('/recette/ajouter', name: 'recette_ajouter')]
    public function ajouter(): Response
    {
        return $this->render('recette/recette_ajouter.html.twig', [
            'controller_name' => 'RecetteController',
        ]);
    }
    #[Route('/recette/{user_name}/{titre}', name: 'recette_detail')]
    public function detail(string $userName, string $titre): Response
    {
        $recette = $this->doctrine->getRepository(Recette::class)->findOneBy(['nom' => $titre]);

        $user = $this->doctrine->getRepository(User::class)->findOneBy(['user_name' => $userName]);

        $vars = [
            'recette' => $recette,
            'user_name' => $user
        ];

        return $this->render('recette/recette_detail.html.twig', $vars);
    }
}