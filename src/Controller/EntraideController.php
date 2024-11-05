<?php

namespace App\Controller;

use App\Entity\Commentaire;
use DateTime;
use App\Entity\User;
use App\Entity\Question;
use App\Form\CommentaireType;
use App\Form\QuestionType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EntraideController extends AbstractController
{
    private $doctrine;
    private $slugger;

    public function __construct(ManagerRegistry $doctrine, SluggerInterface $slugger)
    {
        $this->doctrine = $doctrine;
        $this->slugger = $slugger;
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
            'questions' => $questions,
        ];

        return $this->render('entraide/entraide.html.twig', $vars);
    }

    #[Route('/entraide/poster', name: 'entraide_ajouter')]
    public function ajouter(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Créer une nouvelle question
        $question = new Question();

        // Créer le formulaire
        $form = $this->createForm(QuestionType::class, $question);

        // Traiter la requête
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $this->slugger->slug($question->getTitre())->lower();
            $question->setSlug($slug);
        
            // Vérifiez que l'utilisateur est connecté
            $user = $this->getUser();
            if (!$user) {
                $this->addFlash('error', 'Vous devez être connecté pour poser une question.');
                return $this->redirectToRoute('connexion'); // Redirigez vers la page de connexion
            }

            // Associez l'utilisateur connecté
            $question->setUser($user);

            // Définir la date de publication
            $question->setDatePublication(new DateTime());
        
            // Gestion de l'image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                // Après avoir persisté l'entité, on obtient l'ID de la question
                $entityManager->persist($question);
                $entityManager->flush();
        
                // Maintenant que l'ID est généré, créez le dossier de l'utilisateur
                $questionID = $question->getId();
                $questionDir = $this->getParameter('questions_directory') . '/' . $questionID;
                mkdir($questionDir, 0755, true);
        
                // Déplacer le fichier image dans le sous-dossier de la question
                $newFileName = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($questionDir, $newFileName);
        
                // Enregistrez le nom de fichier dans la base de données
                $question->setImage($newFileName);
            }
        
            // Enregistrer la question après avoir défini l'image
            $entityManager->persist($question);
            $entityManager->flush();
        
            return $this->redirectToRoute('entraide');
        }
        

        return $this->render('entraide/entraide_ajouter.html.twig', [
            'form' => $form,
        ]);
    }
    
    #[Route('/entraide/d/{user_slug}/{question_slug}', name: 'entraide_detail')]
    public function detail(string $user_slug, string $question_slug): Response
    {
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['slug' => $user_slug]);
        $question = $this->doctrine->getRepository(Question::class)->findOneBy(['slug' => $question_slug]);
        $commentaires = $question->getCommentaire();

        $vars = [
            'question' => $question,
            'user' => $user,
            'commentaires' => $commentaires
        ];
    
        return $this->render('entraide/entraide_detail.html.twig', $vars);
    }

    #[Route('/entraide/reponse/{question_slug}', name: 'entraide_reponse_question')]
    public function question(Request $request, string $question_slug): Response
    {
        $question = $this->doctrine->getRepository(Question::class)->findOneBy(['slug' => $question_slug]);
    
        if (!$question) {
            throw $this->createNotFoundException("Question non trouvée.");
        }
    
        // Récupérer le pseudo de l'utilisateur ayant posé la question
        $pseudoUser = $question->getUser()->getPseudo();
        $titreQuestion = $question->getTitre();
    
        // Créer un nouveau Commentaire
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Associez le commentaire à l'utilisateur et à la question existants
            $user = $this->getUser();
            $commentaire->setUser($user);

            $commentaire->setQuestion($question);

            // Génération du slug pour le commentaire
            $commentaireSlug = $this->slugger->slug($commentaire->getTitre())->lower();
            $commentaire->setSlug($commentaireSlug);
    
            $em = $this->doctrine->getManager();
            $em->persist($commentaire);
            $em->flush();
    
            return $this->redirectToRoute('entraide_detail', [
                'user_slug' => $question->getUser()->getSlug(),
                'question_slug' => $question_slug,
            ]);
        }
    
        return $this->render('entraide/entraide_reponse_question.html.twig', [
            'question' => $question,
            'pseudoUser' => $pseudoUser,
            'titreQuestion' => $titreQuestion,
            'form' => $form->createView()
        ]);
    }    

    #[Route('/entraide/reponse/{commentaire_slug}', name: 'entraide_reponse_commentaire')]
    public function commentaire(string $commentaire_slug): Response
    {
        $commentaire = $this->doctrine->getRepository(Commentaire::class)->findOneBy(['slug' => $commentaire_slug]);

        $vars = [
            'commentaire' => $commentaire,
        ];
    
        return $this->render('entraide/entraide_reponse_commentaire.html.twig', $vars);
    }
}