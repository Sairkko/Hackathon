<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $region = null;

    #[ORM\Column(length: 255)]
    private ?string $millesime = null;

    #[ORM\Column(length: 255)]
    private ?string $cepage = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\ManyToMany(targetEntity: Atelier::class, mappedBy: 'products')]
    private Collection $ateliers;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    public function __construct()
    {
        $this->ateliers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getMillesime(): ?string
    {
        return $this->millesime;
    }

    public function setMillesime(string $millesime): static
    {
        $this->millesime = $millesime;

        return $this;
    }

    public function getCepage(): ?string
    {
        return $this->cepage;
    }

    public function setCepage(string $cepage): static
    {
        $this->cepage = $cepage;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Atelier>
     */
    public function getAteliers(): Collection
    {
        return $this->ateliers;
    }

    public function addAtelier(Atelier $atelier): static
    {
        if (!$this->ateliers->contains($atelier)) {
            $this->ateliers->add($atelier);
            $atelier->addProduct($this);
        }

        return $this;
    }

    public function removeAtelier(Atelier $atelier): static
    {
        if ($this->ateliers->removeElement($atelier)) {
            $atelier->removeProduct($this);
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
