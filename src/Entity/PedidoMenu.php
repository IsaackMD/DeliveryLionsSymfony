<?php

namespace App\Entity;

use App\Repository\PedidoMenuRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PedidoMenuRepository::class)]
class PedidoMenu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'pedidoMenus')]
    private ?Pedido $Pedido = null;

    #[ORM\ManyToOne(inversedBy: 'pedidoMenus')]
    private ?Menu $Menu = null;

    #[ORM\Column]
    private ?int $Cantidad = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPedido(): ?Pedido
    {
        return $this->Pedido;
    }

    public function setPedido(?Pedido $Pedido): static
    {
        $this->Pedido = $Pedido;

        return $this;
    }

    public function getMenu(): ?Menu
    {
        return $this->Menu;
    }

    public function setMenu(?Menu $Menu): static
    {
        $this->Menu = $Menu;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->Cantidad;
    }

    public function setCantidad(int $Cantidad): static
    {
        $this->Cantidad = $Cantidad;

        return $this;
    }


}
