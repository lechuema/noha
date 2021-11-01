<?php

namespace App\Controller\Admin;

use App\Entity\Cliente;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class ClienteCrudController extends AbstractCrudController
{
    /**
     * @Route("/cliente", name="cliente")
     */
    public static function getEntityFqcn(): string
    {
        return Cliente::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('mail'),
            TextField::new('telefono'),
            TextField::new('nombre'),
            TextField::new('apellido'),
            TextField::new('direccion'),

        ];
    }
    
}
