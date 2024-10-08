<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EntraideController extends AbstractController
{
    #[Route('/entraide', name: 'entraide')]
    public function index(): Response
    {
        return $this->render('entraide/entraide.html.twig', [
            'controller_name' => 'EntraideController',
        ]);
    }
    #[Route('/entraide/poster', name: 'entraide_ajouter')]
    public function ajouter(): Response
    {
        return $this->render('entraide/entraide_ajouter.html.twig', [
            'controller_name' => 'EntraideController',
        ]);
    }
    #[Route('/entraide/{user_name}/{titre}', name: 'entraide_detail')]
    public function detail(string $userName, string $titre): Response
    {
        return $this->render('entraide/entraide_detail.html.twig', [
            'controller_name' => 'EntraideController',
        ]);
    }
}
