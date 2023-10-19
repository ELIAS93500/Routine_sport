<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    // Route pour afficher le formulaire de contact
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        // Création d'un formulaire de contact en utilisant ContactType
        $form = $this->createForm(ContactType::class);

        // Gestion de la soumission du formulaire
        $form->handleRequest($request);

        // Vérification si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données du formulaire
            $data = $form->getData();

            // Création d'un objet Email pour envoyer le message
            $message = (new Email())
                ->from($data->getEmail()) // Utilisation de l'e-mail fourni dans le formulaire
                ->to('elias.dejoux@gmail.com')
                ->subject('Nouveau message de contact')
                ->html(
                    // Utilisation d'une vue Twig pour le corps du message
                    $this->renderView(
                        'email/contact.html.twig',
                        ['data' => $data]
                    )
                );

            // Envoi du message par le service de messagerie
            $mailer->send($message);

            // Ajout d'un message flash pour informer de la réussite de l'envoi
            $this->addFlash('success', 'Votre message a été envoyé avec succès.');

            // Redirection vers la page d'accueil
            return $this->redirectToRoute('app_home');
        }

        // Rendu de la vue du formulaire de contact
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
