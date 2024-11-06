<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'accueil')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        // Créer le formulaire de contact
        $form = $this->createForm(ContactType::class);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du formulaire
            $data = $form->getData();

            // Créer l'email
            $email = (new Email())
                ->from($data['email'])
                ->to('a.waerks@gmail.com') // Remplacez par l'email de destination
                ->subject('Nouveau message de contact')
                ->html('<p><strong>Nom:</strong> '.$data['name'].'</p><p><strong>Prénom:</strong> '.$data['forname'].'</p><p><strong>Message:</strong> '.$data['message'].'</p>');

            // Envoyer l'email
            $mailer->send($email);

            // Ajouter un message flash pour confirmer l'envoi
            $this->addFlash('success', 'Votre message a été envoyé avec succès');

            // Rediriger vers la page d'accueil après l'envoi
            return $this->redirectToRoute('accueil');
        }

        // Rendre la vue d'accueil avec le formulaire
        return $this->render('accueil/accueil.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
