<?php

namespace App\Form;

use App\Entity\Formule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FormulaireType extends AbstractType
{
    // Construction du formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Ajout des champs du formulaire avec leurs types respectifs
        $builder
            ->add('type', TextType::class, [
                'required' => false, // Champ non obligatoire
                'label' => 'Type', // Étiquette du champ dans le formulaire
                'attr' => [
                    'placeholder' => 'Saisissez le type' // Placeholder du champ
                ]
            ])
            ->add('prix', NumberType::class, [
                'required' => false, // Champ non obligatoire
                'label' => 'Prix', // Étiquette du champ dans le formulaire
                'attr' => [
                    'placeholder' => 'Saisissez le prix' // Placeholder du champ
                ]
            ])
            ->add('description', TextType::class, [
                'required' => false, // Champ non obligatoire
                'label' => 'Description', // Étiquette du champ dans le formulaire
                'attr' => [
                    'placeholder' => 'Saisissez la description de votre annonce' // Placeholder du champ
                ]
            ]);
    }

    // Configuration des options du formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configuration de la classe de données associée au formulaire (Formule::class)
        $resolver->setDefaults([
            'data_class' => Formule::class,
        ]);
    }
}
