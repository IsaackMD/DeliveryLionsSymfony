<?php

namespace App\Entity;

use App\Repository\NegocioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NegocioRepository::class)]
class Negocio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Negocio = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'negocio')]
    private Collection $Usuario;

    /**
     * @var Collection<int, CatComida>
     */
    #[ORM\OneToMany(targetEntity: CatComida::class, mappedBy: 'negocio')]
    private Collection $CatComida;

    #[ORM\Column]
    private ?bool $Estatus = null;

    /**
     * @var Collection<int, Menu>
     */
    #[ORM\OneToMany(targetEntity: Menu::class, mappedBy: 'Negocio')]
    private Collection $menus;

    #[ORM\Column(length: 255)]
    private ?string $imagen = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    public function __construct()
    {
        $this->Usuario = new ArrayCollection();
        $this->CatComida = new ArrayCollection();
        $this->menus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNegocio(): ?string
    {
        return $this->Negocio;
    }

    public function setNegocio(string $Negocio): static
    {
        $this->Negocio = $Negocio;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsuario(): Collection
    {
        return $this->Usuario;
    }

    public function addUsuario(User $usuario): static
    {
        if (!$this->Usuario->contains($usuario)) {
            $this->Usuario->add($usuario);
            $usuario->setNegocio($this);
        }

        return $this;
    }

    public function removeUsuario(User $usuario): static
    {
        if ($this->Usuario->removeElement($usuario)) {
            // set the owning side to null (unless already changed)
            if ($usuario->getNegocio() === $this) {
                $usuario->setNegocio(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CatComida>
     */
    public function getCatComida(): Collection
    {
        return $this->CatComida;
    }

    public function addCatComida(CatComida $catComida): static
    {
        if (!$this->CatComida->contains($catComida)) {
            $this->CatComida->add($catComida);
            $catComida->setNegocio($this);
        }

        return $this;
    }

    public function removeCatComida(CatComida $catComida): static
    {
        if ($this->CatComida->removeElement($catComida)) {
            // set the owning side to null (unless already changed)
            if ($catComida->getNegocio() === $this) {
                $catComida->setNegocio(null);
            }
        }

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
            $menu->setNegocio($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): static
    {
        if ($this->menus->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getNegocio() === $this) {
                $menu->setNegocio(null);
            }
        }

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): static
    {
        $this->imagen = $imagen;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }
}
