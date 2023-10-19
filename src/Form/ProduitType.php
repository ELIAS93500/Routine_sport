<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProduitType extends AbstractType
{
    // Construction du formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Ajout des champs du formulaire avec leurs types respectifs

        // Champ pour le nom du produit
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Saisissez votre nom'
                ]
            ])

        // Champ pour le numéro de voie
            ->add('street_number', NumberType::class, [
                'required' => false,
                'label' => 'Numéro de voie',
                'attr' => [
                    'placeholder' => 'Saisissez le numéro de voie'
                ]
            ])

        // Champ pour le libellé de la voie
            ->add('street_name', TextType::class, [
                'required' => false,
                'label' => 'Libellé de voie',
                'attr' => [
                    'placeholder' => 'Saisissez le libellé de voie'
                ]
            ])

        // Champ ville avec type TextType
            ->add('city', TextType::class, [
                'required' => false,
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Saisissez la ville'
                ]
            ])

            // Champ pour le numéro de téléphone
            ->add('tel', TextType::class,[
                'required'=>false,
                'label'=>'Téléphone',
                'attr'=>[
                    'placeholder'=>'Saisissez votre numéro de téléphone'
                ]
            ])

        // Champ pour la catégorie (liaison avec l'entité Category)
            ->add('category', EntityType::class, [
                'class' => \App\Entity\Category::class,
                'label' => 'Catégorie',
                'choice_label' => 'name',
                'placeholder' => 'Saisissez le genre de catégorie clubs/salles',
            ])

        // Champ pour le type de sport (liaison avec l'entité SportType)
            ->add('sport_type', EntityType::class, [
                'class' => \App\Entity\SportType::class,
                'choice_label' => 'name',
                'label' => 'Type de sport',
                'placeholder' => 'Saisissez le type de sport'
            ])

        // Champ pour le prix du produit
            ->add('price', NumberType::class, [
                'required' => false,
                'label' => 'Prix',
                'attr' => [
                    'placeholder' => 'Saisissez le prix'
                ]
            ])

        // Champ pour la description du produit
            ->add('description', TextType::class, [
                'required' => false,
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Saisissez la description de votre annonce'
                ]
            ])

            // Champs pour les médias (images)
            ->add('media1', FileType::class, [
                'label' => 'Média1',
                'required' => false,
                'multiple' => false,
                'mapped' => false
            ])
            ->add('media2', FileType::class, [
                'label' => 'Média2',
                'required' => false,
                'multiple' => false,
                'mapped' => false
            ])
            ->add('media3', FileType::class, [
                'label' => 'Média3',
                'required' => false,
                'multiple' => false,
                'mapped' => false
            ])

        // Champ pour le slogan du produit
            ->add('slogan', TextType::class, [
                'required' => false,
                'label' => 'Slogan',
                'attr' => [
                    'placeholder' => 'Saisissez une phrase motivante'
                ]
            ])

        // Champ pour la date de début de l'annonce
            ->add('date_start', DateType::class, [
                'required' => false,
                'label' => 'Date début de l\'annonce',
                'attr' => [
                    'placeholder' => 'Saisissez la date de début de votre annonce'
                ]
            ])

        // Bouton de validation du formulaire
            ->add('Valider', SubmitType::class);
    }

    // Configuration des options du formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configuration de la classe de données associée au formulaire (Produit::class)
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
