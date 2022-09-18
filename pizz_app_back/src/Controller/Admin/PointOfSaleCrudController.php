<?php

namespace App\Controller\Admin;

use App\Entity\PointOfSale;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PointOfSaleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PointOfSale::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
