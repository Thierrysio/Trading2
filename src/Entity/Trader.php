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

    /*
Explication de la méthode calculerValeurTotalePortefeuilles() :

Cette méthode initialise une variable $valeurTotale à 0.
Elle parcourt la liste des portefeuilles du trader.
Pour chaque portefeuille, elle appelle la méthode calculerValeurPortefeuille() pour obtenir sa valeur.
Elle additionne cette valeur à $valeurTotale.
Enfin, elle retourne la valeur totale de tous les portefeuilles du trader.
    */
    public function calculerValeurTotalePortefeuilles():float {
        $valeurTotale = 0.0;

        // Parcourt chaque portefeuille du trader
        foreach ($this->portefeuilles as $portefeuille) {
            // Ajoute la valeur du portefeuille à la valeur totale
            $valeurTotale += $portefeuille->calculerValeurPortefeuille();
        }

        return $valeurTotale;
    }

    private function verifierFormatEmail($email) {
        // Utilisation d'une regex pour vérifier le format standard d'un email
        $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        return preg_match($pattern, $email);
    }

    /**
     * Liste toutes les actions possédées par le trader sans doublons.
     *
     * @return array Collection des actions possédées.
     */
    public function listerToutesLesActionsPossedees() {
        $actionsPossedees = [];
        foreach ($this->portefeuilles as $portefeuille) {
            foreach ($portefeuille->getActions() as $action) {
                $symbole = $action->getSymbole();
                if (!isset($actionsPossedees[$symbole])) {
                    $actionsPossedees[$symbole] = $action;
                }
            }
        }
        return array_values($actionsPossedees);
    }
}
