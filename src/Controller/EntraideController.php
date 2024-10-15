<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Question;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EntraideController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/entraide', name: 'entraide')]
    public function index(): Response
    {
        // Chercher les users
        $users = $this->doctrine->getRepository(User::class)->findAll();

        // Chercher les questions
        $questions = $this->doctrine->getRepository(Question::class)->findAll();

        $vars = [
            'users' => $users,
            'questions' => $questions
        ];

        return $this->render('entraide/entraide.html.twig', $vars);
    }

    #[Route('/entraide/poster', name: 'entraide_ajouter')]
    public function ajouter(): Response
    {
        return $this->render('entraide/entraide_ajouter.html.twig');
    }
    
    #[Route('/entraide/d/{user_slug}/{recette_slug}', name: 'entraide_detail')]
    public function detail(string $userName, string $titre): Response
    {
    
        return $this->render('entraide/entraide_detail.html.twig');
    }
}