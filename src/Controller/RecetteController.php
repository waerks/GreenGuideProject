<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\User;
use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecetteController extends AbstractController
{
    private $doctrine;
    private $slugger;

    public function __construct(ManagerRegistry $doctrine, SluggerInterface $slugger)
    {
        $this->doctrine = $doctrine;
        $this->slugger = $slugger;
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
    public function ajouter(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Créer une nouvelle recette
        $recette = new Recette();

        // Créer le formulaire
        $form = $this->createForm(RecetteType::class, $recette);

        // Traiter la requête
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $this->slugger->slug($recette->getNom())->lower();
            $recette->setSlug($slug);

            // Vérifiez que l'utilisateur est connecté
            $user = $this->getUser();
            if (!$user) {
                $this->addFlash('error', 'Vous devez être connecté pour poser une question.');
                return $this->redirectToRoute('connexion'); // Redirigez vers la page de connexion
            }

            // Associer l'utilisateur connecté
            $recette->setUser($user);

            // Gestion de l'image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                // Maintenant que l'ID est généré, créez le dossier de l'utilisateur
                $recetteID = $recette->getId();
                $recetteDir = $this->getParameter('recettes_directory') . '/' . $recetteID;
                if (!is_dir($recetteDir)) {
                    mkdir($recetteDir, 0755, true); // Créer le répertoire uniquement s'il n'existe pas
                }

                // Déplacer le fichier image dans le sous-dossier de la recette
                $newFileName = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($recetteDir, $newFileName);

                // Enregistrer le nom de fichier dans la base de données
                $recette->setImage($newFileName);
            }

            // Enregistrer la recette après avoir défini l'image
            $entityManager->persist($recette);
            $entityManager->flush();

            return $this->redirectToRoute(route: 'recette');
        }

        return $this->render('recette/recette_ajouter.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/recette/{id}/like', name: 'recette_like', methods: ['POST'])]
    public function like(Recette $recette, EntityManagerInterface $entityManager, LikeRepository $likeRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Vous devez être connecté pour liker une recette.'], 403);
        }

        // Vérifie si l'utilisateur a déjà liké cette recette
        $like = $likeRepository->findOneBy(['recette' => $recette, 'user' => $user]);

        if ($like) {
            // Si un like existe, on le supprime
            $entityManager->remove($like);
            $entityManager->flush();
            $isLiked = false;
        } else {
            // Sinon, on crée un like
            $like = new Like();
            $like->setRecette($recette);
            $like->setUser($user);
            $entityManager->persist($like);
            $entityManager->flush();
            $isLiked = true;
        }

        // Retourne le nombre de likes actuel et l'état du like
        return $this->json([
            'likeCount' => $recette->getLikeCount(),
            'isLiked' => $isLiked
        ]);
    }

    #[Route('/recette/d/{user_slug}/{recette_slug}', name: 'recette_detail')]
    public function detail(string $user_slug, string $recette_slug, LikeRepository $likeRepository): Response
    {
    
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['slug' => $user_slug]);
        $recette = $this->doctrine->getRepository(Recette::class)->findOneBy(['slug' => $recette_slug]);

        $user = $this->getUser();

        // Vérifie si l'utilisateur a liké cette recette
        $isLiked = false;
        if ($user) {
            $like = $likeRepository->findOneBy(['recette' => $recette, 'user' => $user]);
            if ($like) {
                $isLiked = true;
            }
        }

        $vars = [
            'recette' => $recette,
            'user' => $user,
            'isLiked' => $isLiked
        ];

        return $this->render('recette/recette_detail.html.twig', $vars);
    }
}