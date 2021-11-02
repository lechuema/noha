<?php

namespace App\Controller\Admin;

use App\Entity\EstadoPedido;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EstadoPedidoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EstadoPedido::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $descripcion = TextField::new('descripcion');
        
        switch ($pageName){
            case Crud::PAGE_INDEX:{
                return [$descripcion];
                break;
            }
            case Crud::PAGE_NEW:{
                return [$descripcion];
                break;
            }
            case Crud::PAGE_EDIT:{
                 return [$descripcion];
                break;
            }
            case Crud::PAGE_DETAIL:{
                return [$descripcion];
                break;
            }
        }
    }
}
