<?php

namespace App\Entity;

use App\Repository\PedidoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PedidoRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Pedido
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'pedidos')]
    private ?User $Usuario = null;    

    #[ORM\Column(length: 255)]
    private ?string $Estatus = null;

    #[ORM\Column]
    private ?float $Precio = null;

    #[ORM\OneToMany(targetEntity: PedidoMenu::class, mappedBy: 'Pedido')]
    private Collection $pedidoMenus;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fecha_pedido = null;

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->setFechaPedido(new \DateTime());
    }

    public function __construct()
    {
        $this->pedidoMenus = new ArrayCollection();
        $this->Usuario = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?User
    {
        return $this->Usuario;
    }

    public function setUsuario(?User $usuario): static
    {
        $this->Usuario = $usuario;
        return $this;
    }

    public function getEstatus(): ?string
    {
        return $this->Estatus;
    }

    public function setEstatus(string $Estatus): static
    {
        $this->Estatus = $Estatus;
        return $this;
    }

    public function getPrecio(): ?float
    {
        return $this->Precio;
    }

    public function setPrecio(float $Precio): static
    {
        $this->Precio = $Precio;
        return $this;
    }

    public function getPedidoMenus(): Collection
    {
        return $this->pedidoMenus;
    }

    public function addPedidoMenu(PedidoMenu $pedidoMenu): static
    {
        if (!$this->pedidoMenus->contains($pedidoMenu)) {
            $this->pedidoMenus->add($pedidoMenu);
            $pedidoMenu->setPedido($this);
        }
        return $this;
    }

    public function removePedidoMenu(PedidoMenu $pedidoMenu): static
    {
        if ($this->pedidoMenus->removeElement($pedidoMenu)) {
            if ($pedidoMenu->getPedido() === $this) {
                $pedidoMenu->setPedido(null);
            }
        }
        return $this;
    }

    public function getFechaPedido(): ?\DateTimeInterface
    {
        return $this->fecha_pedido;
    }

    public function setFechaPedido(?\DateTimeInterface $fecha_pedido): static
    {
        $this->fecha_pedido = $fecha_pedido;
        return $this;
    }

    public function calcularTotal(): float
    {
        $total = 0.0;
        foreach ($this->getPedidoMenus() as $pedidoMenu) {
            $cantidad = $pedidoMenu->getCantidad() ?? 0;
            $precio = $pedidoMenu->getMenu()->getPrecio() ?? 0.0;
            $total += $cantidad * $precio;
        }
        return $total;
    }
}
