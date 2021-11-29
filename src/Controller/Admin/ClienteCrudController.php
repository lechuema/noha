<?php

namespace App\Controller\Admin;

use App\Entity\Cliente;
use App\Entity\Pedido;
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

    public function createEntity(string $entityFqcn)
    {
        $cliente=new Cliente();
        $cliente->setTelefono('+549');
        return $cliente;
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
