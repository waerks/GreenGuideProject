<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'inscription')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        // Créer un nouvel Utilisateur
        $user = new User();

        // Créer le formulaire
        $form = $this->createForm(RegistrationFormType::class, $user);

        // Traiter la requête
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Attribuer le rôle à l'User
            $user->setRoles(['ROLE_USER']);

            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // Encodez le mot de passe
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            // Gérez le téléchargement de l'avatar
            $avatarFile = $form->get('avatar')->getData();

            if ($avatarFile) {
                // Créer un dossier pour l'utilisateur avec son ID
                $userDir = $this->getParameter('avatars_directory') . '/' . $user->getId();
                mkdir($userDir, 0755, true);

                // Créer un nom de fichier unique pour l'avatar
                $newFilename = uniqid().'.'.$avatarFile->guessExtension();

                // Déplacer le fichier dans le dossier de l'utilisateur
                $avatarFile->move($userDir, $newFilename);

                // Stocker le chemin de l'avatar dans l'utilisateur
                $user->setAvatar($newFilename);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('accueil');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}