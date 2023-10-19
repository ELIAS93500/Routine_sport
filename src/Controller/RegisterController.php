<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    // Action pour gérer l'inscription d'un nouvel utilisateur
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        // Crée une nouvelle instance de l'entité User
        $user = new User();

        // Crée un formulaire en utilisant le type RegisterType et associe l'entité User
        $form = $this->createForm(RegisterType::class, $user);
        // Traite la requête pour remplir le formulaire avec les données soumises
        $form->handleRequest($request);

        // Vérifie si le formulaire a été soumis et s'il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Hash le mot de passe avant de l'enregistrer dans la base de données
            $hashedPassword = $hasher->hashPassword($user, $form->get('password')->getData());

            // Associe le mot de passe hashé à l'utilisateur
            $user->setPassword($hashedPassword);

            // Enregistre l'objet User dans la base de données
            $manager->persist($user);
            // Exécute les opérations enregistrées
            $manager->flush();

            // Redirige vers la page de connexion après une inscription réussie
            return $this->redirectToRoute('app_login');
        }

        // Affiche le formulaire d'inscription à l'utilisateur
        return $this->render('register/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
