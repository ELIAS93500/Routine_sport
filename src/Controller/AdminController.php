<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    // Action pour gérer le téléchargement d'un produit
    #[Route('/coach/upload-product', name: 'upload_product')]
    public function uploadProduct(Request $request, EntityManagerInterface $manager): Response
    {
        // Créer une nouvelle instance de Produit
        $product = new Produit();

        // Créer un formulaire basé sur ProduitType et associer le produit
        $form = $this->createForm(ProduitType::class, $product);
        $form->handleRequest($request);

        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Parcourir les champs de média (media1, media2, media3)
            foreach (['media1', 'media2', 'media3'] as $mediaField) {
                // Obtenir le fichier téléchargé
                $imageFile = $form[$mediaField]->getData();

                if ($imageFile) {
                    // Générer un nom de fichier unique et le déplacer vers le répertoire de téléchargement
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                    try {
                        $imageFile->move(
                            $this->getParameter('upload_dir'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // Gérer l'erreur si le déplacement du fichier échoue
                    }

                    // Mettre à jour le champ de média dans l'entité Produit avec le nouveau nom de fichier
                    $setterMethod = 'set' . ucfirst($mediaField);
                    $product->$setterMethod($newFilename);
                }
            }

            // Associer le produit à l'utilisateur actuel et le sauvegarder en base de données
            $product->setUser($this->getUser());
            $manager->persist($product);
            $manager->flush();

            // Rediriger vers la page d'accueil avec un message flash de succès
            $this->addFlash('success', 'Produit ajouté avec succès.');

            return $this->redirectToRoute('app_home');
        }

        // Afficher le formulaire de téléchargement
        return $this->render('admin/upload_product.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Action pour gérer l'édition d'un produit
    #[Route('/coach/edit-product/{id}', name: 'edit_product')]
    public function editProduct(Request $request, EntityManagerInterface $manager, $id): Response
    {
        // Récupérer le produit à éditer en fonction de l'ID
        $product = $manager->getRepository(Produit::class)->find($id);

        // Gérer l'erreur si le produit n'est pas trouvé
        if (!$product) {
            throw $this->createNotFoundException('Le produit avec l\'ID ' . $id . ' n\'existe pas.');
        }

        // Créer un formulaire basé sur ProduitType et associer le produit
        $form = $this->createForm(ProduitType::class, $product);
        $form->handleRequest($request);

        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Parcourir les champs de média et effectuer des opérations similaires à l'upload
            foreach (['media1', 'media2', 'media3'] as $mediaField) {
                // Obtenir le fichier téléchargé
                $imageFile = $form[$mediaField]->getData();

                if ($imageFile) {
                    // Générer un nom de fichier unique et le déplacer vers le répertoire de téléchargement
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                    try {
                        $imageFile->move(
                            $this->getParameter('upload_dir'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // Gérer l'erreur si le déplacement du fichier échoue
                    }

                    // Mettre à jour le produit en base de données avec le nouveau nom de fichier
                    $setterMethod = 'set' . ucfirst($mediaField);
                    $product->$setterMethod($newFilename);
                }
            }

            // Mettre à jour le produit en base de données
            $manager->persist($product);
            $manager->flush();

            // Rediriger vers la page du compte avec un message flash de succès
            $this->addFlash('success', 'Produit modifié avec succès.');

            return $this->redirectToRoute('myaccount_index');
        }

        // Afficher le formulaire d'édition du produit
        return $this->render('admin/edit_product.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }

    // Action pour supprimer un produit
    #[Route('/coach/delete-product/{id}', name: 'delete_product')]
    public function deleteProduct($id, EntityManagerInterface $manager): Response
    {
        // Récupérer le produit à supprimer en fonction de l'ID
        $product = $manager->getRepository(Produit::class)->find($id);

        // Gérer l'erreur si le produit n'est pas trouvé
        if (!$product) {
            // Rediriger vers la page des annonces avec un message flash d'erreur
            $this->addFlash('error', 'Produit non trouvé.');

            return $this->redirectToRoute('app_mes_annonces');
        }

        // Supprimer le produit de la base de données
        $manager->remove($product);
        $manager->flush();

        // Rediriger vers la page des annonces avec un message flash de succès
        $this->addFlash('success', 'Produit supprimé avec succès.');

        return $this->redirectToRoute('app_mes_annonces');
    }

    // Action pour afficher la page des annonces
    #[Route('/coach/annonces', name: 'app_mes_annonces')]
    public function annonces(EntityManagerInterface $manager): Response
    {
        // Afficher la page des annonces
        return $this->render('admin/mesAnnonces.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
