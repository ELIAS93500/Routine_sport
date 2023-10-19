<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    // Construction du formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Ajout du champ 'name' avec le type TextType
        $builder
            ->add('name', TextType::class, [
                'required' => false, // Le champ n'est pas obligatoire
                'label' => 'Nom de la catégorie', // Étiquette du champ dans le formulaire
                'attr' => [
                    'placeholder' => 'Saisissez la catégorie' // Placeholder du champ
                ]
            ])
            ->add('Valider', SubmitType::class); // Ajout du bouton de validation
    }

    // Configuration des options du formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configuration de la classe de données associée au formulaire (Category::class)
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
