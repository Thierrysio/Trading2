<?php

namespace App\Entity;

use App\Repository\ActionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActionRepository::class)]
class Action
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $symbole = null;

    #[ORM\Column(length: 255)]
    private ?string $nomEntreprise = null;

    #[ORM\Column]
    private ?float $prix = null;

    /**
     * @var Collection<int, Portefeuille>
     */
    #[ORM\ManyToMany(targetEntity: Portefeuille::class, inversedBy: 'actions')]
    private Collection $portefeuilles;

    public function __construct()
    {
        $this->portefeuilles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSymbole(): ?string
    {
        return $this->symbole;
    }

    public function setSymbole(string $symbole): static
    {
        $this->symbole = $symbole;

        return $this;
    }

    public function getNomEntreprise(): ?string
    {
        return $this->nomEntreprise;
    }

    public function setNomEntreprise(string $nomEntreprise): static
    {
        $this->nomEntreprise = $nomEntreprise;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

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
        }

        return $this;
    }

    public function removePortefeuille(Portefeuille $portefeuille): static
    {
        $this->portefeuilles->removeElement($portefeuille);

        return $this;
    }

    public function calculerQuantiteTotaleDansPortefeuilles(): int
    {
        return 0;
        //initialiser $quantiteTotale à 0
        //parcourir tous les portefeuillesqui contiennent cette action
        //pour chaque portefeuille, je parcours ses transactions
        //si le type es "Achat", elle ajoute la quantité à $quantiteTotale
        //si le type es "Vente", elle soustrait la quantité à $quantiteTotale
        // enfin elle retourne la quantite totale de cette action détenue dans tous les portefeuilles
    }
}
