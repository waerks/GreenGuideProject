<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EntraideController extends AbstractController
{
    #[Route('/entraide', name: 'app_entraide')]
    public function index(): Response
    {
        return $this->render('entraide/index.html.twig', [
            'controller_name' => 'EntraideController',
        ]);
    }
}
