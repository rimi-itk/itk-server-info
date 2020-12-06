<?php

namespace App\Controller\Admin;

use App\Admin\Field\JsonField;
use App\Entity\Website;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class WebsiteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Website::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPaginatorPageSize(100)
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('domain')
                ->hideOnForm(),
            AssociationField::new('server')
                ->hideOnForm(),
            TextField::new('siteRoot')
                ->hideOnForm(),
            TextField::new('type')
                ->hideOnForm()
                ->setTemplatePath('admin/website/list/filter.html.twig'),
            TextField::new('version')
                ->hideOnForm()
                ->setTemplatePath('admin/website/list/filter.html.twig'),
            AssociationField::new('audiences')
                ->setTemplatePath('admin/website/list/filter.html.twig'),
            TextareaField::new('comments'),
            DateTimeField::new('updatedAt')
                ->hideOnForm(),
            JsonField::new('data')
                ->onlyOnDetail()
                ->setTemplatePath('admin/website/data.html.twig'),
            TextareaField::new('search')
                ->onlyOnDetail(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->disable(Action::NEW, Action::DELETE);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('type')
            ->add('version')
            ->add('audiences')
            ;
    }

    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        $alias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere($alias.'.enabled = true');

        return $queryBuilder;
    }
}
