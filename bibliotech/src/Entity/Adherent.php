<?php

namespace App\Entity;

use App\Repository\AdherentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdherentRepository::class)]
class Adherent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $ville = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $email = null;

    /**
     * @var Collection<int, Emprunt>
     */
    #[ORM\OneToMany(targetEntity: Emprunt::class, mappedBy: 'id_adherent')]
    private Collection $emprunts_associes;

    public function __construct()
    {
        $this->emprunts_associes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

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
            $empruntsAssocy->setIdAdherent($this);
        }

        return $this;
    }

    public function removeEmpruntsAssocy(Emprunt $empruntsAssocy): static
    {
        if ($this->emprunts_associes->removeElement($empruntsAssocy)) {
            // set the owning side to null (unless already changed)
            if ($empruntsAssocy->getIdAdherent() === $this) {
                $empruntsAssocy->setIdAdherent(null);
            }
        }

        return $this;
    }
}
