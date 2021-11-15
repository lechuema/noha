<?php

namespace App\Controller;

use App\Entity\Cliente;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
