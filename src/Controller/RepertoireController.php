<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RepertoireController extends AbstractController
{
    #[Route('/repertoire', name: 'repertoire')]
    public function index(): Response
    {
        return $this->render('repertoire/repertoire.html.twig', [
            'controller_name' => 'RepertoireController',
        ]);
    }
    #[Route('/repertoire/{category}/{nom}', name: 'repertoire_detail', requirements: ['category' => 'fruit|legume|plante'])]
    public function detail(string $category, string $nom): Response
    {
        return $this->render('repertoire/repertoire_detail.html.twig', [
            'controller_name' => 'RepertoireController',
        ]);
    }
}
