<?php

namespace App\Entity;

use App\Repository\DetallePedidoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DetallePedidoRepository::class)
 */
class DetallePedido
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    /**
     * @ORM\Column(type="float")
     */
    private $precio_venta;

    /**
     * @ORM\ManyToOne(targetEntity=Producto::class, inversedBy="detallePedido")
     * @ORM\JoinColumn(nullable=false)
     */
    private $producto_id;

    /**
     * @ORM\ManyToOne(targetEntity=Pedido::class, inversedBy="detallePedido")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pedido_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getPrecioVenta(): ?float
    {
        return $this->precio_venta;
    }

    public function setPrecioVenta(float $precio_venta): self
    {
        $this->precio_venta = $precio_venta;

        return $this;
    }

    public function getProductoId(): ?Producto
    {
        return $this->producto_id;
    }

    public function setProductoId(?Producto $producto_id): self
    {
        $this->producto_id = $producto_id;

        return $this;
    }

    public function getPedidoId(): ?Pedido
    {
        return $this->pedido_id;
    }

    public function setPedidoId(?Pedido $pedido_id): self
    {
        $this->pedido_id = $pedido_id;

        return $this;
    }

    public function __toString(){
        return $this->getProductoId()->getDescripcion().' - Cantidad:'.$this->getCantidad().' - PrecioParcial $'.$this->getPrecioVenta() ;
    }
}
