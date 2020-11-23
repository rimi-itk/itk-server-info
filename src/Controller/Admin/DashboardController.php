<?php

namespace App\Controller\Admin;

use App\Entity\Audience;
use App\Entity\Server;
use App\Entity\Website;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();

        return $this->redirect($routeBuilder->setController(WebsiteCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Itk Server Info')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Websites', 'fa fa-folder-open', Website::class);
        yield MenuItem::linkToCrud('Servers', 'fa fa-folder-open', Server::class);

        yield MenuItem::section('Misc');
        yield MenuItem::linkToCrud('Audience', 'fa fa-folder-open', Audience::class);

        yield MenuItem::section('Export');
        yield MenuItem::linktoRoute('Website (CSV)', 'fa fa-folder-open', 'api_websites_get_collection', [
            '_format' => 'csv',
            'pagination' => false,
            'enabled' => true,
        ]);
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setMenuItems([
                // Remove the logout menu item.
            ]);
    }
}
