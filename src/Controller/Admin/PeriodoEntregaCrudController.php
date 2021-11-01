<?php

namespace App\Controller\Admin;

use App\Entity\PeriodoEntrega;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PeriodoEntregaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PeriodoEntrega::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $horaDesde = TextField::new('horaDesde');
        $horaHasta =  TextField::new('horaHasta');
        
        switch ($pageName){
            case Crud::PAGE_INDEX:{
                return [$horaDesde,$horaHasta];
                break;
            }
            case Crud::PAGE_NEW:{
                return [$horaDesde,$horaHasta];
                break;
            }
            case Crud::PAGE_EDIT:{
                 return [$horaDesde,$horaHasta];
                break;
            }
            case Crud::PAGE_DETAIL:{
                 return [$horaDesde,$horaHasta];
                break;
            }
        }
    }
}
