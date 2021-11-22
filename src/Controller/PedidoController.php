<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\Pedido;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

class PedidoController extends AbstractController
{
    /**
     * @Route("/pedido", name="pedido")
     */
    public function index(): Response
    {
        return $this->render('pedido/index.html.twig', [
            'controller_name' => 'PedidoController',
        ]);
    }

    /**
     * @Route("/buscarCliente",options={"expose"=true}, name="buscarCliente")
     */
    public function buscarCliente(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();
            $id=$request->request->get('id');
            $cli = $em->getRepository(Cliente::class)->find($id);
            if($cli)
            {
                $nombreCli=$cli->getNombre();
                $apellidoCli=$cli->getApellido();
                $direccionCli=$cli->getDireccion();
                $telefonoCli=$cli->getTelefono();
                $mailCli=$cli->getMail();
            }

                return new JsonResponse(array('nombreCliente' => $nombreCli,
                    'apellidoCliente'=>$apellidoCli,
                    'direccionCliente'=>$direccionCli,
                    'telefonoCliente'=>$telefonoCli,
                    'mailCliente'=>$mailCli
                                                    
                ));

        }else{
            throw new Exception('Petición invalida');
        }
    }


    /**
     * @Route("/verficarMail",options={"expose"=true}, name="verficarMail")
     */
    public function verficarMail(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();
            $mail=$request->request->get('mail');
            $cli = $em->getRepository(Cliente::class)->findBy(['mail'=>$mail]);
            if($cli)
            {
                $resultado='EXISTE';

            }else
            {
                $resultado='NOEXISTE';
            }

            return new JsonResponse(array('resultado' => $resultado));

        }else{
            throw new Exception('Petición invalida');
        }
    }

    /**
     * @Route("/imprimirPedido",options={"expose"=true}, name="imprimirPedido")
     */
    public function imprimirPedido(Pedido $context)
    {

       /* foreach ($context->getDetallePedido() as $item) {
            dump($item);
        }
        die;*/

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);



        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('pedido/index.html.twig', [
            'detalle'=>$context->getDetallePedido(),
            'precioTotal' => $context->getPrecioTotal(),
            'nombre'=>$context->getCliente()->getNombre(),
            'apellido'=>$context->getCliente()->getApellido(),
            'domicilio'=>$context->getDireccionEntrega(),
            'fechaEntrega'=>$context->getFechaEntrega(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A2', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("pedido.pdf", [
            "Attachment" => false
        ]);

    }



}
