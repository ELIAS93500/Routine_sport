<?php

namespace App\Controller;

use App\Entity\SportType;
use App\Form\SportTType;
use App\Repository\SportTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/sport/type')]
class SportTypeController extends AbstractController
{
    // Action pour créer ou mettre à jour un type de sport
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/update/{id}', name: 'type_update')]
    #[Route('/', name: 'type_create')]
    public function index(Request $request, EntityManagerInterface $manager, SportTypeRepository $repository, $id = null): Response
    {
        // Récupération de tous les types de sport
        $types = $repository->findAll();

        // Initialisation d'un nouveau type de sport ou récupération d'un type existant
        if ($id) {
            $type = $repository->find($id);
        } else {
            $type = new SportType();
        }

        // Création du formulaire en utilisant la classe SportTType
        $formulaire_type = $this->createForm(SportTType::class, $type);

        // Traitement de la soumission du formulaire
        $formulaire_type->handleRequest($request);

        // Vérification de la soumission et de la validité du formulaire
        if ($formulaire_type->isSubmitted() && $formulaire_type->isValid()) {
            // Persiste le type de sport dans la base de données
            $manager->persist($type);
            $manager->flush();
            // Ajoute un message flash pour informer de la réussite de l'opération
            $this->addFlash('info', 'Opération réalisée avec succès');
            // Redirection vers la route de création de type de sport
            return $this->redirectToRoute('type_create');
        }

        // Rendu de la vue avec le formulaire, la liste des types de sport et un titre
        return $this->render('sport_type/index.html.twig', [
            'formulaire_type' => $formulaire_type->createView(),
            'types' => $types,
            'title' => 'Gestion des types de sport'
        ]);
    }

    // Action pour supprimer un type de sport
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/delete/{id}', name: 'sport_type_delete')]
    public function delete_type(SportType $type, EntityManagerInterface $manager): Response
    {
        // Supprime le type de sport de la base de données
        $manager->remove($type);
        $manager->flush();
        // Ajoute un message flash pour informer de la réussite de la suppression
        $this->addFlash('info', 'Opération réalisée avec succès');

        // Redirection vers la route de création de type de sport
        return $this->redirectToRoute('type_create');
    }
}
