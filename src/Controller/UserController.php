<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    // Action pour afficher la page utilisateur
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        // Rendre la vue de la page utilisateur avec un tableau de données
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
