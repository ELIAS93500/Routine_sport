<?php

namespace App\Form;

use App\dto\RoleToArrayTransformer as DtoRoleToArrayTransformer;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterType extends AbstractType
{
    // Fonction pour construire le formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Ajout des champs du formulaire avec leurs types respectifs

        // Champ nom avec type TextType
        $builder
            ->add('last_name', TextType::class,[
                'required'=>false,
                'label'=>'Nom',
                'attr'=>[
                    'placeholder'=>'Saisissez Votre nom'
                ]
            ]) 

        // Champ prénom avec type TextType
            ->add('first_name', TextType::class,[
                'required'=>false,
                'label'=>'Prénom',
                'attr'=>[
                    'placeholder'=>'Saisissez votre prénom'
                ]
            ])

        // Champ date de naissance avec type BirthdayType
            ->add('birth_day', BirthdayType::class,[
                'required'=>false,
                'label'=>'Date de naissance',
                'widget'=>'single_text', // Affiche le champ comme un champ de texte simple
                'attr' => [
                    'class' => 'datetimepicker-input w-100'
                ]
            ])
            
        // Champ email avec type EmailType
            ->add('email', EmailType::class,[
                'required'=>false, // Champ email n'est pas obligatoire
                'label'=>'Email', // Libellé du champ
                'attr'=>[
                    'placeholder'=>'Saisissez votre email' // Placeholder du champ
                ]
            ])

        // Champ mot de passe avec type PasswordType
            ->add('password', PasswordType::class,[
                'required'=>false,
                'label'=>'Mot de passe',
                'attr'=>[
                    'placeholder'=>'Saisissez un mot de passe'
                ]
            ])

        // Champ roles avec type ChoiceType
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'label' => 'Types',
                'choices' => [
                    'Recherche d\'un coach ou d\'un club'=>'ROLE_USER',
                    'Je m\'inscris en tant que coach'=> 'ROLE_COACH' ,
                    'Je m\'inscris en tant que dirigeant d\'un club' => 'ROLE_CLUB' 
                ],
                'multiple'=>false,
                'expanded'=>true
            ])            
            
        // Champ téléphone avec type TextType
            ->add('tel', TextType::class,[
                'required'=>false,
                'label'=>'Téléphone',
                'attr'=>[
                    'placeholder'=>'Saisissez un numéro de téléphone'
                ]
            ])

        // Champ numéro de voie avec type NumberType
            ->add('street_number', NumberType::class,[
                'required'=>false,
                'label'=>'Numéro de voie',
                'attr'=>[
                    'placeholder'=>'Saisissez le numéro de voie'
                ]
            ])

        // Champ libellé de voie avec type TextType
            ->add('street_name', TextType::class,[
                'required'=>false,
                'label'=>'Libellé de voie',
                'attr'=>[
                    'placeholder'=>'Saisissez le libellé de voie'
                ]
            ])

        // Champ code postal avec type NumberType
            ->add('zip_code', NumberType::class,[
                'required'=>false,
                'label'=>'Code postal',
                'attr'=>[
                    'placeholder'=>'Saisissez le code postal'
                ]
            ])

        // Champ ville avec type TextType
            ->add('city', TextType::class,[
                'required'=>false,
                'label'=>'Ville',
                'attr'=>[
                    'placeholder'=>'Saisissez la ville'
                ]
            ])

        // Bouton de soumission du formulaire avec type SubmitType
            ->add('Validez', SubmitType::class) 
        ;

        // Transformer le champ 'roles' en tableau pour le traitement des rôles
        $builder->get('roles')->addModelTransformer(new DtoRoleToArrayTransformer());
    }

    // Fonction pour configurer les options du formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class, // Classe d'entité associée au formulaire
        ]);
    }
}
