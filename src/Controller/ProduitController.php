<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;

class ProduitController extends AbstractController
{
    // Action pour afficher les détails d'un produit
    #[Route('/produit/{id}', name: 'produit_show')]
    public function show(Produit $produit): Response
    {
        // Rend la vue des détails du produit en passant le produit spécifique
        return $this->render('produit/index.html.twig', [
            'produit' => $produit,
        ]);
    }
}
