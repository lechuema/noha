<?php

namespace App\Entity;

use App\Repository\PeriodoEntregaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PeriodoEntregaRepository::class)
 */
class PeriodoEntrega
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $horaDesde;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $horaHasta;

    /**
     * @ORM\OneToMany(targetEntity=Pedido::class, mappedBy="periodoEntrega")
     */
    private $pedidos;

    public function __construct()
    {
        $this->pedidos = new ArrayCollection();
    }

    public function __toString(){
        return $this->horaDesde.''.$this->horaHasta;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHoraDesde(): ?string
    {
        return $this->horaDesde;
    }

    public function setHoraDesde(string $horaDesde): self
    {
        $this->horaDesde = $horaDesde;

        return $this;
    }

    public function getHoraHasta(): ?string
    {
        return $this->horaHasta;
    }

    public function setHoraHasta(string $horaHasta): self
    {
        $this->horaHasta = $horaHasta;

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
            $pedido->setPeriodoEntrega($this);
        }

        return $this;
    }

    public function removePedido(Pedido $pedido): self
    {
        if ($this->pedidos->removeElement($pedido)) {
            // set the owning side to null (unless already changed)
            if ($pedido->getPeriodoEntrega() === $this) {
                $pedido->setPeriodoEntrega(null);
            }
        }

        return $this;
    }
}
