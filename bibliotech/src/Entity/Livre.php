<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $titre = null;

    #[ORM\Column(length: 100)]
    private ?string $auteur = null;

    #[ORM\Column(length: 50)]
    private ?string $genre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $resume = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $couverture = null;

    #[ORM\Column]
    private ?int $exemplaires = null;

    /**
     * @var Collection<int, Emprunt>
     */
    #[ORM\OneToMany(targetEntity: Emprunt::class, mappedBy: 'id_livre')]
    private Collection $emprunts_associes;

    #[ORM\Column]
    private ?int $Annee = null;

    public function __construct()
    {
        $this->emprunts_associes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(?string $resume): static
    {
        $this->resume = $resume;

        return $this;
    }

    public function getCouverture(): ?string
    {
        return $this->couverture;
    }

    public function setCouverture(?string $couverture): static
    {
        $this->couverture = $couverture;

        return $this;
    }

    public function getExemplaires(): ?int
    {
        return $this->exemplaires;
    }

    public function setExemplaires(int $exemplaires): static
    {
        $this->exemplaires = $exemplaires;

        return $this;
    }

    /**
     * @return Collection<int, Emprunt>
     */
    public function getEmpruntsAssocies(): Collection
    {
        return $this->emprunts_associes;
    }

    public function addEmpruntsAssocy(Emprunt $empruntsAssocy): static
    {
        if (!$this->emprunts_associes->contains($empruntsAssocy)) {
            $this->emprunts_associes->add($empruntsAssocy);
            $empruntsAssocy->setIdLivre($this);
        }

        return $this;
    }

    public function removeEmpruntsAssocy(Emprunt $empruntsAssocy): static
    {
        if ($this->emprunts_associes->removeElement($empruntsAssocy)) {
            // set the owning side to null (unless already changed)
            if ($empruntsAssocy->getIdLivre() === $this) {
                $empruntsAssocy->setIdLivre(null);
            }
        }

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->Annee;
    }

    public function setAnnee(int $Annee): static
    {
        $this->Annee = $Annee;

        return $this;
    }
}
