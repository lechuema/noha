<?php

namespace App\Controller\Admin;

use App\Entity\Pedido;
use Doctrine\Common\Collections\ArrayCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Asset;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use App\Entity\Cliente;
use App\Entity\EstadoPedido;
use App\Entity\PeriodoEntrega;
use App\Entity\Producto;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
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
           ->add(EntityFilter::new('estadoPedido'))
       ;
    }


    public function configureAssets(Assets $assets): Assets
    {

        return $assets

            //->addJsFile('assets/crud/campoPedido.js');
            ->addJsFile(Asset::new('assets/crud/campoPedido.js')->defer())
            ->addJsFile(Asset::new('bundles/fosjsrouting/js/router.min.js')->defer())
            ->addJsFile(Asset::new('https://code.jquery.com/jquery-3.2.1.min.js')->defer());

    }

   /* public function configureCrud(Crud $crud): Crud
    {
        return Crud::new()


            ->overrideTemplates([

                'crud/new' => 'custom_fields/productos.html.twig',
            ])
            ;
    }*/

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        $cliente=AssociationField::new('cliente')->autocomplete();
        $productos = AssociationField::new('productos');
        //$productos=CollectionField::new('productos');
        $productosDetail=AssociationField::new('productos')->formatValue(function ($value, $entity) {
            $str = $entity->getProductos()[0];
            for ($i = 1; $i < $entity->getProductos()->count(); $i++) {
                $str = $str . ", " . $entity->getProductos()[$i];
            }
            return $str;
        });
        $observaciones = TextEditorField::new('observaciones');
        $precioTotal = NumberField::new('precioTotal');
        $fechaEntrega = DateTimeField::new('fechaEntrega');
        $fechaRealizacion = DateTimeField::new('fechaRealizacion')->setSortable(true)->setFormTypeOption('disabled', 'disabled');
        $direccionEntrega = TextField::new('direccionEntrega');
        $retira = BooleanField::new('retiraPorLocal');
        $estadoPedido = AssociationField::new('estadoPedido');
      //  $estadoPedido = AssociationField::new('estadoPedido')->setQueryBuilder(function($queryBuilder) {
        //    $queryBuilder
          //      ->andWhere('entity.id=1')
            //;
        //});
        $nombreCliente = TextField::new('nombreCliente');
        $apellidoCliente=TextField::new('apellidoCliente');
        $direccionCliente=TextField::new('direccionCliente');
        $telefonoCliente=TextField::new('telefonoCliente');
        $mailCliente=TextField::new('mailCliente');

        switch ($pageName) {
            case Crud::PAGE_INDEX:
            {
                return [$cliente, $productos, $precioTotal, $estadoPedido, $retira, $fechaEntrega, $fechaRealizacion, $observaciones];
                break;
            }
            case Crud::PAGE_NEW:
            {
                return [$cliente,$telefonoCliente,$nombreCliente,$apellidoCliente,$direccionCliente,$mailCliente, $productos, $precioTotal, $retira, $fechaEntrega, $observaciones,$estadoPedido];
                break;
            }
            case Crud::PAGE_EDIT:
            {
                return [$cliente, $productos, $precioTotal, $estadoPedido, $retira, $fechaEntrega, $fechaRealizacion, $observaciones];
                break;
            }
            case Crud::PAGE_DETAIL:
            {
                return [$cliente, $productosDetail, $precioTotal, $estadoPedido, $retira, $fechaEntrega, $fechaRealizacion, $observaciones];
                break;
            }
        }


    }


}
