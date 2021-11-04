<?php

namespace App\Controller\Admin;

use App\Entity\Pedido;
use Doctrine\Common\Collections\ArrayCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Asset;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use App\Entity\Cliente;
use App\Entity\EstadoPedido;
use App\Entity\PeriodoEntrega;
use App\Entity\Producto;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;


class PedidoCrudController extends AbstractCrudController
{
    
    
    public static function getEntityFqcn(): string
    {
        return Pedido::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $pedido = new Pedido();
        $pedido->setFechaRealizacion(new \DateTime());
        $pedido->setFechaEntrega(new \DateTime());
        return $pedido;
    }

    public function configureFilters(Filters $filters): Filters
    {
       return $filters
           ->add(EntityFilter::new('cliente'))
       ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return Crud::new()


            ->overrideTemplates([

                'crud/new' => 'custom_fields/productos.html.twig',
            ])
            ;
    }

    public function configureAssets(Assets $assets): Assets
    {

        return $assets

            //->addJsFile('assets/crud/campoPedido.js');
            ->addJsFile(Asset::new('assets/crud/campoPedido.js')->defer());
    }
    
    public function configureFields(string $pageName): iterable
    {

        //$cliente=AssociationField::new('cliente')->setTemplatePath('custom_fields/cliente.html.twig');
        $cliente = AssociationField::new('cliente')->autocomplete();
        //$productos=AssociationField::new('productos')->setTemplatePath('custom_fields/productos.html.twig');
        $productos = AssociationField::new('productos')->setFormTypeOptions([
            'block_name' => 'custom_producto',
        ]);;
        $observaciones = TextEditorField::new('observaciones');
        $precioTotal = NumberField::new('precioTotal')->setFormTypeOption('disabled', 'disabled');
        $fechaEntrega = DateTimeField::new('fechaEntrega');
        $fechaRealizacion = DateTimeField::new('fechaRealizacion')->setSortable(true)->setFormTypeOption('disabled', 'disabled');
        $direccionEntrega = TextField::new('direccionEntrega');
        $retira = BooleanField::new('retiraPorLocal');
        $estadoPedido = AssociationField::new('estadoPedido');

        switch ($pageName) {
            case Crud::PAGE_INDEX:
            {
                return [$cliente, $productos, $precioTotal, $estadoPedido, $observaciones, $fechaEntrega, $fechaRealizacion, $retira];
                break;
            }
            case Crud::PAGE_NEW:
            {
                return [$cliente, $productos, $precioTotal, $estadoPedido, $observaciones, $fechaEntrega, $fechaRealizacion, $retira];
                break;
            }
            case Crud::PAGE_EDIT:
            {
                return [$cliente, $productos, $precioTotal, $estadoPedido, $observaciones, $fechaEntrega, $fechaRealizacion, $retira];
                break;
            }
            case Crud::PAGE_DETAIL:
            {
                return [$cliente, $productos, $observaciones, $precioTotal, $estadoPedido, $fechaEntrega, $fechaRealizacion, $retira];
                break;
            }
        }


    }

   /* public function createEntity(string $entityFqcn)
    {
        $pedido = new Pedido();
        $pedido->setEstadoPedido(new EstadoPedido());
        $pedido->set($this->getUser())

        return $article;
    }*/
}
