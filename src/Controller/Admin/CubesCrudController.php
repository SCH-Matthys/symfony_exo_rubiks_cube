<?php

namespace App\Controller\Admin;

use App\Entity\Cubes;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CubesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cubes::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->setDisabled(),
            TextField::new('name'),
            TextField::new('format'),
            NumberField::new('price'),
            TextEditorField::new('Description'),
            AssociationField::new('category','Catégories'),
            AssociationField::new('colors','Couleurs')
            ->setFormTypeOptions([  'by_reference' => false,
                                    'multiple' => true,
                                    'choice_label' => 'name',
                                ]),
        ];
    }
}
