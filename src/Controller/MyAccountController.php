<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MyAccountController extends AbstractController
{
    // Page d'accueil du compte utilisateur
    #[Route('/myaccount', name: 'myaccount_index')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        // Rend la vue du compte utilisateur en passant l'utilisateur connecté
        return $this->render('my_account/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    // Action pour mettre à jour le profil utilisateur
    #[Route('/myaccount_update', name: 'myaccount_update')]
    public function updateProfil(Request $request, EntityManagerInterface $manager): Response
    {
        // Récupère l'utilisateur connecté
        $user = $this->getUser();

        // Crée le formulaire en utilisant la classe RegisterType
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        // Vérifie si le formulaire est soumis
        if ($form->isSubmitted()) {
            // Persiste les modifications de l'utilisateur dans la base de données
            $manager->persist($user);
            $manager->flush();

            // Ajoute un message flash pour informer de la réussite de l'opération
            $this->addFlash('success', 'Compte modifié avec succès!');

            // Redirige vers la page d'accueil du compte utilisateur
            return $this->redirectToRoute('myaccount_index');
        }

        // Rend la vue de mise à jour du profil en passant le formulaire
        return $this->render('my_account/update_profil.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Action pour supprimer le compte utilisateur
    #[Route('/myaccount/delete', name: 'myaccount_delete')]
    public function deleteAccount(Request $request, EntityManagerInterface $manager): Response
    {
        // Récupère l'utilisateur connecté
        $user = $manager->getRepository(User::class)->findBy(['id' => $this->getUser()->getId()]);

        // Supprime l'utilisateur de la base de données
        $manager->remove($user[0]);
        $manager->flush();

        // Ajoute un message flash pour informer de la réussite de la suppression du compte
        $this->addFlash('success', 'Compte supprimé avec succès!');

        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_home');
    }
}
