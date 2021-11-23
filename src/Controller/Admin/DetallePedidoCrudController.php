<?php

namespace App\Controller\Admin;

use App\Entity\DetallePedido;
use App\Entity\Pedido;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DetallePedidoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DetallePedido::class;
    }



   /* public function configureFields(string $pageName): iterable
    {
        $producto=AssociationField::new('producto_id')->setColumns(4)->setRequired(true);
        $cantidad=TextField::new('cantidad')->setColumns(12);
        $precio=TextField::new('precio_venta')->setColumns(4);
        return [$producto,$cantidad,$precio];
    }*/

}
