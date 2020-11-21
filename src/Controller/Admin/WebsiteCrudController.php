<?php

namespace App\Controller\Admin;

use App\Entity\Website;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class WebsiteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Website::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('domain'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW, Action::DELETE);
    }
}
