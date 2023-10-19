<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Repository\CategoryRepository;
use App\Repository\FormuleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    // Action pour afficher la page d'accueil
    #[Route('/', name: 'app_home')]
    public function index(
        ProduitRepository $produitRepository,
        FormuleRepository $formuleRepository,
        CategoryRepository $categoryRepository,
        Request $request
    ): Response {
        // Récupérer tous les produits depuis la base de données
        $produits = $produitRepository->findAll();

        // Récupérer toutes les catégories depuis la base de données
        $categories = $categoryRepository->findAll();

        // Récupérer toutes les formules depuis la base de données
        $formules = $formuleRepository->findAll();

        // Vérifier si un filtre a été appliqué
        $filter = $request->query->get('filter');
        if ($filter) {
            // Filtrer les produits par catégorie si un filtre est appliqué
            $produits = $produitRepository->findBy(['category' => $filter]);
        }

        // Rendre la vue d'accueil en passant la liste des produits, catégories et formules
        return $this->render('home/index.html.twig', [
            'produits' => $produits,
            'categories' => $categories,
            'formules' => $formules,
        ]);
    }
}
