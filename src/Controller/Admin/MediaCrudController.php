<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MediaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Media::class;
    }

  
    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('path')
            ->SetBasePath('assets/upload/') // destination du fichier image
            ->setUploadDir('public/assets/upload/') // destination final du fichier image
            ->setUploadedFileNamePattern('[randomhash].[extension]') //selection de l extention du fichier ET GENERATION D'UNE CHAINE DE CARACTERE
            ->setRequired(false),
        ];
    }
    
}
