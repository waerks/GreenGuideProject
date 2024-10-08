<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RepertoireController extends AbstractController
{
    #[Route('/repertoire', name: 'app_repertoire')]
    public function index(): Response
    {
        return $this->render('repertoire/index.html.twig', [
            'controller_name' => 'RepertoireController',
        ]);
    }
}
