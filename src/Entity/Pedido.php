<?php

namespace App\Entity;

use App\Repository\PedidoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PedidoRepository::class)
 */
class Pedido
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
    private $precioTotal;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaRealizacion;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaEntrega;

    /**
     * @ORM\Column(type="text")
     */
    private $direccionEntrega;

    /**
     * @ORM\Column(type="boolean")
     */
    private $retiraPorLocal;

    /**
     * @ORM\Column(type="text")
     */
    private $observaciones;

    /**
     * @ORM\ManyToOne(targetEntity=Cliente::class, inversedBy="pedidos")
     */
    private $cliente;

    /**
     * @ORM\ManyToMany(targetEntity=Producto::class, inversedBy="pedidos")
     */
    private $productos;

    /**
     * @ORM\ManyToOne(targetEntity=PeriodoEntrega::class, inversedBy="pedidos")
     */
    private $periodoEntrega;

    /**
     * @ORM\ManyToOne(targetEntity=EstadoPedido::class, inversedBy="pedidos")
     */
    private $estadoPedido;

    public function __construct()
    {
        $this->productos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrecioTotal(): ?float
    {
        return $this->precioTotal;
    }

    public function setPrecioTotal(float $precioTotal): self
    {
        $this->precioTotal = $precioTotal;

        return $this;
    }

    public function getFechaRealizacion(): ?\DateTimeInterface
    {
        $this->fechaRealizacion= new \DateTime();
        return $this->fechaRealizacion;
    }

    public function setFechaRealizacion(\DateTimeInterface $fechaRealizacion): self
    {
        $this->fechaRealizacion = $fechaRealizacion;

        return $this;
    }

    public function getFechaEntrega(): ?\DateTimeInterface
    {
        return $this->fechaEntrega;
    }

    public function setFechaEntrega(\DateTimeInterface $fechaEntrega): self
    {
        $this->fechaEntrega = $fechaEntrega;

        return $this;
    }

    public function getDireccionEntrega(): ?string
    {
        return $this->direccionEntrega;
    }

    public function setDireccionEntrega(?string $direccionEntrega): self
    {
        $this->direccionEntrega = $direccionEntrega;

        return $this;
    }

    public function getRetiraPorLocal(): ?bool
    {
        return $this->retiraPorLocal;
    }

    public function setRetiraPorLocal(bool $retiraPorLocal): self
    {
        $this->retiraPorLocal = $retiraPorLocal;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    public function getCliente(): ?Cliente
    {
        return $this->cliente;
    }

    public function setCliente(?Cliente $cliente): self
    {
        $this->cliente = $cliente;

        return $this;
    }


    public $mailCliente;
    public $telefonoCliente;
    public $nombreCliente;
    public $apellidoCliente;
    public $direccionCliente;

    /**
     * @return Collection|Producto[]
     */
    public function getProductos(): Collection
    {
        return $this->productos;
    }

    public function addProducto(Producto $producto): self
    {
        if (!$this->productos->contains($producto)) {
            $this->productos[] = $producto;
        }

        return $this;
    }

    public function removeProducto(Producto $producto): self
    {
        $this->productos->removeElement($producto);

        return $this;
    }

    public function getPeriodoEntrega(): ?PeriodoEntrega
    {
        return $this->periodoEntrega;
    }

    public function setPeriodoEntrega(?PeriodoEntrega $periodoEntrega): self
    {
        $this->periodoEntrega = $periodoEntrega;

        return $this;
    }

    public function getEstadoPedido(): ?EstadoPedido
    {
        return $this->estadoPedido;
    }

    public function setEstadoPedido(?EstadoPedido $estadoPedido): self
    {
        $this->estadoPedido = $estadoPedido;

        return $this;
    }
}
