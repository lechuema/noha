<?php

namespace App\Controller\Admin;

use App\Entity\Producto;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class ProductoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Producto::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $observaciones = TextField::new('descripcion');
        $precioActual =  NumberField::new('precioActual');
        
        switch ($pageName){
            case Crud::PAGE_INDEX:{
                return [$observaciones,$precioActual];
                break;
            }
            case Crud::PAGE_NEW:{
                return [$observaciones,$precioActual];
                break;
            }
            case Crud::PAGE_EDIT:{
                return [$observaciones,$precioActual];
                break;
            }
            case Crud::PAGE_DETAIL:{
                return [$observaciones,$precioActual];
                break;
            }
        }
    }
    
}
