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
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
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
                $cli = new Cliente();
                $cli->setTelefono($entityInstance->getTelefonoCliente());
                $cli->setMail($entityInstance->getMailCliente());
                $cli->setNombre($entityInstance->getNombreCliente());
                $cli->setApellido($entityInstance->getApellidoCliente());
                $cli->setDireccion($entityInstance->getDireccionCliente());
                $em = $this->getDoctrine()->getManager();
                $em->persist($cli);
                $em->flush();
                $entityInstance->setCliente($cli);
                $entityInstance->setDireccionEntrega($cli->getDireccion());

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




    public function configureFields(string $pageName): iterable
    {
        $cliente = AssociationField::new('cliente','Cliente')->autocomplete();
        $productos = AssociationField::new('productos')->setColumns(6)->setRequired(true);
        $productosDetail = AssociationField::new('productos')->formatValue(function ($value, $entity) {
            $str = $entity->getProductos()[0];
            for ($i = 1; $i < $entity->getProductos()->count(); $i++) {
                $str = $str . ", " . $entity->getProductos()[$i];
            }
            return $str;
        });
        $observaciones = TextField::new('observaciones')->setColumns(4)->setRequired(false);;
        $precioTotal = NumberField::new('precioTotal')->setColumns(2);
        $fechaEntrega = DateTimeField::new('fechaEntrega')->setColumns(2);
        $fechaRealizacion = DateTimeField::new('fechaRealizacion')->setSortable(true)->setFormTypeOption('disabled', 'disabled');
        $retira = BooleanField::new('retiraPorLocal')->setColumns(2);
        $estadoPedido = AssociationField::new('estadoPedido')->setColumns(4);
        $nombreCliente = TextField::new('nombreCliente')->setColumns(4)->setRequired(true);;
        $apellidoCliente = TextField::new('apellidoCliente')->setColumns(4)->setRequired(true);
        $direccionCliente = TextField::new('direccionCliente')->setColumns(4)->setRequired(true);;
        $telefonoCliente = TextField::new('telefonoCliente')->setColumns(6)->setRequired(true);;
        $mailCliente = EmailField::new('mailCliente')->setColumns(6)->setRequired(true);;
        $direccionEnvio=TextField::new('direccionEntrega')->setColumns(4);

        switch ($pageName) {
            case Crud::PAGE_INDEX:
            {
                return [$cliente, $productos, $precioTotal, $estadoPedido, $retira, $fechaEntrega,$direccionEnvio, $fechaRealizacion, $observaciones];
                break;
            }
            case Crud::PAGE_NEW:
            {
                return [
                    $cliente, FormField::addPanel('Datos de cliente'),$telefonoCliente, $mailCliente, $nombreCliente, $apellidoCliente, $direccionCliente,FormField::addPanel('Datos de pedido'), $productos, $precioTotal, $retira, $fechaEntrega,$direccionEnvio, $observaciones, $estadoPedido];
                break;
            }
            case Crud::PAGE_EDIT:
            {
                return [$cliente, $productos, $precioTotal, $estadoPedido,$direccionEnvio, $retira, $fechaEntrega, $fechaRealizacion, $observaciones];
                break;
            }
            case Crud::PAGE_DETAIL:
            {
                return [$cliente, $productosDetail, $precioTotal, $estadoPedido, $retira,$direccionEnvio, $fechaEntrega, $fechaRealizacion, $observaciones];
                break;
            }
        }


    }


}
