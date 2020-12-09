<?php

namespace App\Controller\Admin;

use App\Entity\Audience;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AudienceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Audience::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
        ];
    }
}
