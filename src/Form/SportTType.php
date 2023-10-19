<?php

namespace App\Form;

use App\Entity\SportType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SportTType extends AbstractType
{
    // Construction du formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Champ pour le nom du type de sport
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => 'Nom du type de sport (ex: musculation, boxe, natation...)',
                'attr' => [
                    'placeholder' => 'Saisissez un nom de type de sport'
                ]
            ])

        // Bouton de validation du formulaire
            ->add('Valider', SubmitType::class);
    }

    // Configuration des options du formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configuration de la classe de données associée au formulaire (SportType::class)
        $resolver->setDefaults([
            'data_class' => SportType::class,
        ]);
    }
}
