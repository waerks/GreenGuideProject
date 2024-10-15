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
        return $this->render('recette/recette_ajouter.html.twig');
    }

    #[Route('/recette/d/{user_slug}/{recette_slug}', name: 'recette_detail')]
    public function detail(string $user_slug, string $recette_slug): Response
    {
    
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['slug' => $user_slug]);
        $recette = $this->doctrine->getRepository(Recette::class)->findOneBy(['slug' => $recette_slug]);

        $user = $this->getUser();

        $vars = [
            'recette' => $recette,
            'user' => $user
        ];

        return $this->render('recette/recette_detail.html.twig', $vars);
    }
}