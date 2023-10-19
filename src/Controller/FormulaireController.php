<?php

namespace App\Controller;

use App\Entity\Formule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormulaireController extends AbstractController
{
    // Action pour afficher le formulaire d'abonnement en fonction du type
    public function subscribe(Request $request, string $type, EntityManagerInterface $manager): Response
    {
        // Types de formules valides
        $validTypes = ['Pack Membre', 'Pack Pro'];

        // Vérification si le type de formule est valide
        if (!in_array($type, $validTypes)) {
            // Lancer une exception si le type de formule n'est pas valide
            throw $this->createNotFoundException('Type de formule invalide');
        }

        // Récupération de toutes les formules depuis la base de données
        $formules = $manager->getRepository(Formule::class)->findAll();

        // Rendu de la vue du formulaire d'abonnement avec les formules disponibles
        return $this->render('subscription/subscribe.html.twig', [
            'formules' => $formules,
        ]);
    }
}
