<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecetteController extends AbstractController
{
    #[Route('/recette', name: 'recette')]
    public function index(): Response
    {
        return $this->render('recette/recette.html.twig', [
            'controller_name' => 'RecetteController',
        ]);
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
        return $this->render('recette/recette_detail.html.twig', [
            'controller_name' => 'RecetteController',
        ]);
    }
}
