<?php

namespace App\Controller\Admin;

use App\Entity\Pedido;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Asset;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use App\Entity\Cliente;
use App\Entity\EstadoPedido;
use App\Entity\PeriodoEntrega;
use App\Entity\Producto;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
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
use Symfony\Component\HttpFoundation\Request;


class PedidoCrudController extends AbstractCrudController
{
    
    
    public static function getEntityFqcn(): string
    {
        return Pedido::class;
    }

    public function getEstadoPedidoPendiente()
    {
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository(EstadoPedido::class)->find(1);
    }


    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if(!$entityInstance->getCliente())
        {
            $cli=new Cliente();
            $cli->setTelefono($entityInstance->getTelefonoCliente());
            $cli->setMail($entityInstance->getMailCliente());
            $cli->setNombre($entityInstance->getNombreCliente());
            $cli->setApellido($entityInstance->getApellidoCliente());
            $cli->setDireccion($entityInstance->getDireccionCliente());
            $em = $this->getDoctrine()->getManager();
            $em->persist($cli);
            $em->flush();
            $entityInstance->setCliente($cli);
        }
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function createEntity(string $entityFqcn)
    {
        $pedido = new Pedido();
        $pedido->setFechaRealizacion(new \DateTime());
        $pedido->setFechaEntrega(new \DateTime());
        $estado=$this->getEstadoPedidoPendiente();
        $pedido->setEstadoPedido($estado);
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

    public function configureCrud(Crud $crud): Crud
    {
        return $crud

            ->setSearchFields(['cliente.apellido','cliente.telefono'])
            ->setDefaultSort(['fechaEntrega' => 'DESC', 'cliente.apellido' => 'ASC'])
            ->setPaginatorPageSize(10)
            ->setPaginatorRangeSize(4)
            ->setPaginatorUseOutputWalkers(true)
            ->setPaginatorFetchJoinCollection(true)
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ;
    }



    /*public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setCliente'],
        ];
    }

    public function setCliente(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!$entity->getCliente()) {
            $cli=new Cliente();
            $cli->setTelefono('121212');
            $cli->setMail('em@em.com');
            $entity->setCliente($cli);
        }
        /*if (!($entity instanceof Cliente)) {
            $client=new Cliente();
            $client->setApellido('hola');
            $client->setNombre('nombre');
            $client->setMail('mail@llkj.com');
            $client->setTelefono(1212112);
            $entity->setCliente($client);
            return;
        }

        //$client = $entity->getCliente();
       // if(!$client)
        //{

        //}

    }*/

    public function configureFields(string $pageName): iterable
    {
        $cliente = AssociationField::new('cliente')->autocomplete();
        $productos = AssociationField::new('productos');
        $productosDetail = AssociationField::new('productos')->formatValue(function ($value, $entity) {
            $str = $entity->getProductos()[0];
            for ($i = 1; $i < $entity->getProductos()->count(); $i++) {
                $str = $str . ", " . $entity->getProductos()[$i];
            }
            return $str;
        });
        $observaciones = TextField::new('observaciones');
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
        //$estadoPedidoNew=AssociationField::new('estadoPedido');
        $nombreCliente = TextField::new('nombreCliente');
        $apellidoCliente = TextField::new('apellidoCliente');
        $direccionCliente = TextField::new('direccionCliente');
        $telefonoCliente = TextField::new('telefonoCliente');
        $mailCliente = TextField::new('mailCliente');

        switch ($pageName) {
            case Crud::PAGE_INDEX:
            {
                return [$cliente, $productos, $precioTotal, $estadoPedido, $retira, $fechaEntrega, $fechaRealizacion, $observaciones];
                break;
            }
            case Crud::PAGE_NEW:
            {
                return [$cliente, $telefonoCliente, $nombreCliente, $apellidoCliente, $direccionCliente, $mailCliente, $productos, $precioTotal, $retira, $fechaEntrega, $observaciones, $estadoPedido];
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
