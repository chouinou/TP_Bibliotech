<?php

namespace App\Entity;

use App\Repository\EmpruntRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpruntRepository::class)]
class Emprunt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date_emprunt = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $date_retour = null;

    #[ORM\ManyToOne(inversedBy: 'emprunts_associes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Livre $id_livre = null;

    #[ORM\ManyToOne(inversedBy: 'emprunts_associes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adherent $id_adherent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEmprunt(): ?\DateTimeImmutable
    {
        return $this->date_emprunt;
    }

    public function setDateEmprunt(\DateTimeImmutable $date_emprunt): static
    {
        $this->date_emprunt = $date_emprunt;

        return $this;
    }

    public function getDateRetour(): ?\DateTimeImmutable
    {
        return $this->date_retour;
    }

    public function setDateRetour(?\DateTimeImmutable $date_retour): static
    {
        $this->date_retour = $date_retour;

        return $this;
    }

    public function getIdLivre(): ?Livre
    {
        return $this->id_livre;
    }

    public function setIdLivre(?Livre $id_livre): static
    {
        $this->id_livre = $id_livre;

        return $this;
    }

    public function getIdAdherent(): ?Adherent
    {
        return $this->id_adherent;
    }

    public function setIdAdherent(?Adherent $id_adherent): static
    {
        $this->id_adherent = $id_adherent;

        return $this;
    }

    public function estEnCours(): bool
    {
        return $this->date_retour === null;
    }
}
