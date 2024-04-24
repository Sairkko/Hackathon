<?php

namespace App\Entity;

use App\Repository\AtelierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AtelierRepository::class)]
class Atelier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\Column]
    private ?int $limite_participant = null;

    #[ORM\ManyToMany(targetEntity: Reservation::class, mappedBy: 'ateliers')]
    private Collection $reservations;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_inscription_maximum = null;

    #[ORM\ManyToOne(inversedBy: 'ateliers')]
    private ?Ecole $ecole = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $localisation = null;

    #[ORM\ManyToOne(inversedBy: 'ateliers')]
    private ?AtelierContent $atelierContent = null;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(?\DateTimeInterface $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(?\DateTimeInterface $date_fin): static
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getLimiteParticipant(): ?int
    {
        return $this->limite_participant;
    }

    public function setLimiteParticipant(int $limite_participant): static
    {
        $this->limite_participant = $limite_participant;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->addAtelier($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            $reservation->removeAtelier($this);
        }

        return $this;
    }

    public function getDateInscriptionMaximum(): ?\DateTimeInterface
    {
        return $this->date_inscription_maximum;
    }

    public function setDateInscriptionMaximum(?\DateTimeInterface $date_inscription_maximum): static
    {
        $this->date_inscription_maximum = $date_inscription_maximum;

        return $this;
    }

    public function getEcole(): ?Ecole
    {
        return $this->ecole;
    }

    public function setEcole(?Ecole $ecole): static
    {
        $this->ecole = $ecole;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getAtelierContent(): ?AtelierContent
    {
        return $this->atelierContent;
    }

    public function setAtelierContent(?AtelierContent $atelierContent): static
    {
        $this->atelierContent = $atelierContent;

        return $this;
    }
}
