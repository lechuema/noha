<?php

namespace App\Controller\Admin;

use App\Entity\DetallePedido;
use App\Entity\Pedido;
use App\Form\DetallePedidoType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Builder\AssociationBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Asset;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use App\Entity\Cliente;
use App\Entity\EstadoPedido;
use App\Entity\PeriodoEntrega;
use App\Entity\Producto;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
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
use Symfony\Component\Routing\Annotation\Route;

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

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $precioTotal=0;
        foreach ($entityInstance->getDetallePedido() as $linea)
        {
            $precioLinea=$linea->getProductoId()->getPrecioActual() * $linea->getCantidad();
            $precioTotal=$precioTotal+$precioLinea;
            $linea->setPrecioVenta($precioLinea);

        }
        $entityInstance->setPrecioTotal($precioTotal);
        $em = $this->getDoctrine()->getManager();
        $detallesABorrar=$em->getRepository(DetallePedido::class)->findBy(['id'=>$entityInstance->getId()]);
        foreach($detallesABorrar as $detalle)
        {
                $em->remove($detalle);
        }


        $em->persist($entityInstance);
        $em->flush();


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
        $precioTotal=0;
        foreach ($entityInstance->getDetallePedido() as $linea)
        {
            $precioLinea=$linea->getProductoId()->getPrecioActual() * $linea->getCantidad();
            $precioTotal=$precioTotal+$precioLinea;
            $linea->setPrecioVenta($precioLinea);
        }
        $entityInstance->setPrecioTotal($precioTotal);

        parent::persistEntity($entityManager, $entityInstance);

    }



    public function createEntity(string $entityFqcn)
    {
        $pedido = new Pedido();
        $fecha=new \DateTime();
        $pedido->setFechaRealizacion($fecha);
        $pedido->setFechaEntrega($fecha);
        $estado=$this->getEstadoPedidoPendiente();
        $pedido->setEstadoPedido($estado);
        return $pedido;
    }

    public function configureFilters(Filters $filters): Filters
    {
       return $filters
           ->add(EntityFilter::new('cliente'))
           ->add(EntityFilter::new('estadoPedido'))
           ->add('fechaEntrega')

       ;
    }


    public function configureAssets(Assets $assets): Assets
    {

        return $assets
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


    public function armarPedidoParaMensaje(Pedido $pedido):string
    {
        $detalle='';
        foreach ($pedido->getDetallePedido() as $linea)
        {
            $detalle = $detalle.' '.$linea->getProductoId()->getDescripcion().' - Cantidad: '.$linea->getCantidad().' - Precio parcial:$'.$linea->getPrecioVenta().' - ';
        }
        return $detalle;
    }


    public function configureActions(Actions $actions): Actions
    {
        $imprimirOrden = Action::new('viewInvoice', 'Imprimir')
            ->linkToRoute('imprimirPedido', function (Pedido $pedido): array {
                return [
                    'id' => $pedido->getId(),
                    'apellido'=>$pedido->getApellidoCliente(),
                ];
            });

        $enviarOrden = Action::new('enviarOrden', 'Enviar orden')
            ->setHtmlAttributes(['target' => '_blank'])
            ->linkToUrl(function (Pedido $entity) {
                $pedido=$this->armarPedidoParaMensaje($entity);
                return 'https://api.whatsapp.com/send?phone='.$entity->getCliente()->getTelefono().'&text= '.'Estimado/a '.$entity->getCliente()->getNombre() .', te enviamos la confirmación de tu pedido: '.$pedido.'  - Se entrega en: '.$entity->getDireccionEntrega().', el día: '.$entity->getFechaEntrega()->format('Y-m-d').' - Costo total:$'.$entity->getPrecioTotal().'. Hora entrega: '.$entity->getPeriodoEntrega().'. Gracias por confiar en NOHA!';
            });

        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX,$imprimirOrden )
            ->add(Crud::PAGE_INDEX,$enviarOrden )


            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ;
    }




    public function configureFields(string $pageName): iterable
    {
        $cliente = AssociationField::new('cliente','Cliente')->autocomplete()->setSortable(false);
        $observaciones = TextField::new('observaciones')->setColumns(9)->setRequired(false);;
        $precioTotal = NumberField::new('precioTotal')->setColumns(2);
        $fechaEntrega = DateTimeField::new('fechaEntrega')->setColumns(3);
        $fechaRealizacion = DateTimeField::new('fechaRealizacion')->setSortable(true)->setFormTypeOption('disabled', 'disabled');
        $retira = BooleanField::new('retiraPorLocal')->setColumns(3)->setSortable(false);
        $estadoPedido = AssociationField::new('estadoPedido')->setColumns(3)->setSortable(false);
        $nombreCliente = TextField::new('nombreCliente')->setColumns(4)->setRequired(true);;
        $apellidoCliente = TextField::new('apellidoCliente')->setColumns(4)->setRequired(true);
        $direccionCliente = TextField::new('direccionCliente')->setColumns(4)->setRequired(true);
        $telefonoCliente = TextField::new('telefonoCliente')->setColumns(6)->setRequired(true);
        $mailCliente = EmailField::new('mailCliente')->setColumns(6)->setRequired(true);
        $direccionEnvio=TextField::new('direccionEntrega')->setColumns(3);
        $detalle=CollectionField::new('detallePedido')->setEntryType(DetallePedidoType::class)->setRequired(true)->setColumns(12)->addJsFiles('assets/crud/detalle.js');
        $horaEntrega=AssociationField::new('periodoEntrega')->setColumns(3);
        switch ($pageName) {
            case Crud::PAGE_INDEX:
            {
                return [$cliente, $precioTotal, $estadoPedido, $retira, $fechaEntrega,$direccionEnvio, $observaciones];
                break;
            }
            case Crud::PAGE_NEW:
            {
                return [
                    $cliente, FormField::addPanel('Datos de cliente'),$telefonoCliente, $mailCliente, $nombreCliente, $apellidoCliente, $direccionCliente,FormField::addPanel('Datos de pedido'), $detalle, $retira, $fechaEntrega,$horaEntrega,$direccionEnvio, $observaciones, $estadoPedido];
                break;
            }
            case Crud::PAGE_EDIT:
            {
                return [
                    $cliente,FormField::addPanel('Datos de pedido'), $detalle, $retira, $fechaEntrega,$horaEntrega,$direccionEnvio, $observaciones, $estadoPedido];
                break;
            }
            case Crud::PAGE_DETAIL:
            {
                return [FormField::addPanel('Datos de cliente'),$cliente,FormField::addPanel('Datos de pedido'), $detalle, $precioTotal, $retira,$fechaEntrega,$direccionEnvio, $estadoPedido,$horaEntrega,  $fechaRealizacion, $observaciones];
                break;
            }
        }


    }


}
