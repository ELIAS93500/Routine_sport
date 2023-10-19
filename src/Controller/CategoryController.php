<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/category')]
class CategoryController extends AbstractController
{
    // Route pour la création et la mise à jour de catégories
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/', name: 'category_home')]
    #[Route('/create', name: 'category_create')]
    #[Route('/update/{id}', name: 'category_update')]
    public function index(Request $request, EntityManagerInterface $manager, CategoryRepository $repository, $id = null): Response
    {
        // Récupération de toutes les catégories
        $categories = $repository->findAll();

        // Initialisation d'une nouvelle catégorie ou récupération d'une catégorie existante
        if ($id) {
            $category = $repository->find($id);
        } else {
            $category = new Category();
        }

        // Création du formulaire en utilisant la classe CategoryType
        $form = $this->createForm(CategoryType::class, $category);

        // Traitement de la soumission du formulaire
        $form->handleRequest($request);

        // Vérification de la soumission et de la validité du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Persiste la catégorie dans la base de données
            $manager->persist($category);
            $manager->flush();
            // Ajoute un message flash pour informer de la réussite de l'opération
            $this->addFlash('info', 'Opération réalisée avec succès');
            // Redirection vers la route de création de catégorie
            return $this->redirectToRoute('category_create');
        }

        // Rendu de la vue avec le formulaire, la liste des catégories et un titre
        return $this->render('category/index.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories,
            'title' => 'Gestion des catégories clubs ou salles'
        ]);
    }

    // Route pour la suppression d'une catégorie
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/delete/{id}', name: 'category_delete')]
    public function delete(EntityManagerInterface $manager, Category $category): Response
    {
        // Supprime la catégorie de la base de données
        $manager->remove($category);
        $manager->flush();
        // Ajoute un message flash pour informer de la réussite de l'opération
        $this->addFlash('info', 'Opération réalisée avec succès');

        // Redirection vers la route de création de catégorie
        return $this->redirectToRoute('category_create');
    }
}
