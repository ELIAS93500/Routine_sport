<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactType extends AbstractType
{
    // Construction du formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Ajout des champs du formulaire avec leurs types respectifs
        $builder
            ->add('nom', TextType::class, [
                'required' => true, // Champ obligatoire
                'label' => 'Nom', // Étiquette du champ dans le formulaire
            ])
            ->add('email', EmailType::class, [
                'required' => true, // Champ obligatoire
                'label' => 'Email', // Étiquette du champ dans le formulaire
            ])
            ->add('message', TextareaType::class, [
                'required' => true, // Champ obligatoire
                'label' => 'Message', // Étiquette du champ dans le formulaire
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer', // Étiquette du bouton de soumission
            ]);
    }

    // Configuration des options du formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configuration de la classe de données associée au formulaire (Contact::class)
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
