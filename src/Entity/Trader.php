<?php

namespace App\Entity;

use App\Repository\TraderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TraderRepository::class)]
class Trader
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * @var Collection<int, Portefeuille>
     */
    #[ORM\OneToMany(targetEntity: Portefeuille::class, mappedBy: 'proprietaire')]
    private Collection $portefeuilles;

    public function __construct()
    {
        $this->portefeuilles = new ArrayCollection();
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

    /**
     * @return Collection<int, Portefeuille>
     */
    public function getPortefeuilles(): Collection
    {
        return $this->portefeuilles;
    }

    public function addPortefeuille(Portefeuille $portefeuille): static
    {
        if (!$this->portefeuilles->contains($portefeuille)) {
            $this->portefeuilles->add($portefeuille);
            $portefeuille->setProprietaire($this);
        }

        return $this;
    }

    public function removePortefeuille(Portefeuille $portefeuille): static
    {
        if ($this->portefeuilles->removeElement($portefeuille)) {
            // set the owning side to null (unless already changed)
            if ($portefeuille->getProprietaire() === $this) {
                $portefeuille->setProprietaire(null);
            }
        }

        return $this;
    }
}
