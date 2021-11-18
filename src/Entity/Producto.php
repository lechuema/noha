<?php

namespace App\Entity;

use App\Repository\ProductoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductoRepository::class)
 */
class Producto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $precioActual;

    /**
     * @ORM\Column(type="text")
     */
    private $descripcion;

    /**
     * @ORM\ManyToMany(targetEntity=Pedido::class, mappedBy="productos")
     */
    private $pedidos;

    /**
     * @ORM\OneToMany(targetEntity=DetallePedido::class, mappedBy="producto_id")
     */
    private $detallePedido;



    public function __construct()
    {
        $this->pedidos = new ArrayCollection();
        $this->pedidoProducto = new ArrayCollection();
        $this->detallePedido = new ArrayCollection();
    }

    public function __toString(){
        return $this->descripcion.'|'.$this->precioActual;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrecioActual(): ?float
    {
        return $this->precioActual;
    }

    public function setPrecioActual(float $precioActual): self
    {
        $this->precioActual = $precioActual;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * @return Collection|Pedido[]
     */
    public function getPedidos(): Collection
    {
        return $this->pedidos;
    }

    public function addPedido(Pedido $pedido): self
    {
        if (!$this->pedidos->contains($pedido)) {
            $this->pedidos[] = $pedido;
            $pedido->addProducto($this);
        }

        return $this;
    }

    public function removePedido(Pedido $pedido): self
    {
        if ($this->pedidos->removeElement($pedido)) {
            $pedido->removeProducto($this);
        }

        return $this;
    }

    /**
     * @return Collection|DetallePedido[]
     */
    public function getDetallePedido(): Collection
    {
        return $this->detallePedido;
    }

    public function addDetallePedido(DetallePedido $detallePedido): self
    {
        if (!$this->detallePedido->contains($detallePedido)) {
            $this->detallePedido[] = $detallePedido;
            $detallePedido->setProductoId($this);
        }

        return $this;
    }

    public function removeDetallePedido(DetallePedido $detallePedido): self
    {
        if ($this->detallePedido->removeElement($detallePedido)) {
            // set the owning side to null (unless already changed)
            if ($detallePedido->getProductoId() === $this) {
                $detallePedido->setProductoId(null);
            }
        }

        return $this;
    }

}
