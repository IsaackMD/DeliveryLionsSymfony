<?php

namespace App\Entity;

use App\Repository\CatComidaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CatComidaRepository::class)]
class CatComida
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Categoria = null;

    #[ORM\Column]
    private ?bool $Estatus = null;

    #[ORM\ManyToOne(inversedBy: 'CatComida')]
    private ?Negocio $negocio = null;

    #[ORM\Column(length: 255)]
    private ?string $Imagen = null;

    /**
     * @var Collection<int, Menu>
     */
    #[ORM\OneToMany(targetEntity: Menu::class, mappedBy: 'cat_comida')]
    private Collection $menus;

    public function __construct()
    {
        $this->menus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoria(): ?string
    {
        return $this->Categoria;
    }

    public function setCategoria(string $Categoria): static
    {
        $this->Categoria = $Categoria;

        return $this;
    }

    public function isEstatus(): ?bool
    {
        return $this->Estatus;
    }

    public function setEstatus(bool $Estatus): static
    {
        $this->Estatus = $Estatus;

        return $this;
    }

    public function getNegocio(): ?Negocio
    {
        return $this->negocio;
    }

    public function setNegocio(?Negocio $negocio): static
    {
        $this->negocio = $negocio;

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

    /**
     * @return Collection<int, Menu>
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): static
    {
        if (!$this->menus->contains($menu)) {
            $this->menus->add($menu);
            $menu->setCatComida($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): static
    {
        if ($this->menus->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getCatComida() === $this) {
                $menu->setCatComida(null);
            }
        }

        return $this;
    }
}
