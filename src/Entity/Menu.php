<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $NomMenu = null;

    #[ORM\Column]
    private ?float $Precio = null;

    #[ORM\Column(length: 255)]
    private ?string $Descrip = null;

    #[ORM\Column(length: 255)]
    private ?string $Imagen = null;

    #[ORM\Column(length: 255)]
    private ?string $Complemento = null;

    #[ORM\ManyToOne(inversedBy: 'menus')]
    private ?Negocio $Negocio = null;


    #[ORM\ManyToOne(inversedBy: 'menus')]
    private ?CatComida $cat_comida = null;

    /**
     * @var Collection<int, PedidoMenu>
     */
    #[ORM\OneToMany(targetEntity: PedidoMenu::class, mappedBy: 'Menu')]
    private Collection $pedidoMenus;

    public function __construct()
    {
        $this->pedidoMenus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMenu(): ?string
    {
        return $this->NomMenu;
    }

    public function setNomMenu(string $NomMenu): static
    {
        $this->NomMenu = $NomMenu;

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

    public function getDescrip(): ?string
    {
        return $this->Descrip;
    }

    public function setDescrip(string $Descrip): static
    {
        $this->Descrip = $Descrip;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->Imagen;
    }

    public function setImagen(string $Imagen): static
    {
        $this->Imagen = $Imagen;

        return $this;
    }

    public function getComplemento(): ?string
    {
        return $this->Complemento;
    }

    public function setComplemento(string $Complemento): static
    {
        $this->Complemento = $Complemento;

        return $this;
    }

    public function getNegocio(): ?Negocio
    {
        return $this->Negocio;
    }

    public function setNegocio(?Negocio $Negocio): static
    {
        $this->Negocio = $Negocio;

        return $this;
    }


    public function getCatComida(): ?CatComida
    {
        return $this->cat_comida;
    }

    public function setCatComida(?CatComida $cat_comida): static
    {
        $this->cat_comida = $cat_comida;

        return $this;
    }

    /**
     * @return Collection<int, PedidoMenu>
     */
    public function getPedidoMenus(): Collection
    {
        return $this->pedidoMenus;
    }

    public function addPedidoMenu(PedidoMenu $pedidoMenu): static
    {
        if (!$this->pedidoMenus->contains($pedidoMenu)) {
            $this->pedidoMenus->add($pedidoMenu);
            $pedidoMenu->setMenu($this);
        }

        return $this;
    }

    public function removePedidoMenu(PedidoMenu $pedidoMenu): static
    {
        if ($this->pedidoMenus->removeElement($pedidoMenu)) {
            // set the owning side to null (unless already changed)
            if ($pedidoMenu->getMenu() === $this) {
                $pedidoMenu->setMenu(null);
            }
        }

        return $this;
    }
}
