<?php

namespace App\Controller;

use App\Entity\Formule;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripePayController extends AbstractController
{
    // Propriété pour contenir le gestionnaire d'entités
    public $manager;

    // Constructeur pour injecter le gestionnaire d'entités
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    // Action pour initier le processus de paiement Stripe
    #[Route('/stripe/pay/{id}', name: 'app_stripe_pay')]
    public function index(EntityManagerInterface $manager, $id): Response
    {
        // Utilisez find() directement sur le référentiel pour récupérer l'entité par ID
        $entite = $manager->getRepository(Formule::class)->find($id);
        
        // Vérifiez si l'entité existe
        if (!$entite) {
            throw $this->createNotFoundException('Formule non trouvée');
        }
    

        // Définir la clé API Stripe
        Stripe::setApiKey('sk_test_51O2t5WARKiQGzTHdFDIJNovJREby7CaMgq87vdGrqSoOvelGd0k3mjRZqT8NL4QjWMTKTysC5wNkPe21ShMb46qR00nOtxSEf8');

        // Utilisez des identifiants de prix différents pour chaque formule
        $priceId = ($id === '1') ? 'price_1O2tCGARKiQGzTHdnzjN1jcG' : 'price_1O2tCTARKiQGzTHdQqthvwqo';
    
        // Créez une session Stripe pour le paiement
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price' => $priceId,
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_home', ['success' => true], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('app_home', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        
        
        // Rediriger vers la page de paiement Stripe
        return new RedirectResponse($session->url, 303);
    }

    // Action pour gérer le résultat du paiement
    #[Route('/commande/{success}', name: 'commande')]
    public function commande($success = null): Response
    {
        // Vérifier si le paiement a réussi
        if ($success) {
            // Obtenir l'utilisateur actuel
            $utilisateur = $this->getUser();
            // Marquer l'utilisateur comme payé dans la base de données
            $utilisateur->setIspaid(1);
            $this->manager->persist($utilisateur);
            $this->manager->flush();

            // Message flash pour un paiement réussi
            $this->addFlash('success', 'Merci pour votre confiance');
            // Rediriger vers la page d'accueil
            return $this->redirectToRoute('app_home');
        } else {
            // Message flash pour un échec de paiement
            $this->addFlash('danger', 'Un problème est survenu, merci de réitérer votre paiement');
            // Rediriger vers la page d'accueil
            return $this->redirectToRoute('app_home');
        }
    }
}
