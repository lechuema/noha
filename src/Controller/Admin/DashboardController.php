<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use App\Entity\Cliente;
use App\Entity\EstadoPedido;
use App\Entity\Pedido;
use App\Entity\PeriodoEntrega;
use App\Entity\Producto;


class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(PedidoCrudController::class)->generateUrl());    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Noha Admin');
    }

    public function configureMenuItems(): iterable
    {
        //yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linktoCrud('Pedidos', 'fa fa-shopping-cart',Pedido::class);
        yield MenuItem::linktoCrud('Clientes', 'fa fa-user-o',Cliente::class);
        yield MenuItem::linktoCrud('Combos', 'fa fa-lightbulb-o',Producto::class);
        yield MenuItem::linktoCrud('Horarios entrega', 'fa fa-cog fa-fw',PeriodoEntrega::class);
        yield MenuItem::linktoCrud('Estados de pedido', 'fa fa-cog fa-fw',EstadoPedido::class);

        
    }
}
