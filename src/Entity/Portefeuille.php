<?php

namespace App\Entity;

use App\Repository\PortefeuilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

#[ORM\Entity(repositoryClass: PortefeuilleRepository::class)]
class Portefeuille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'portefeuilles')]
    private ?Trader $proprietaire = null;

    /**
     * @var Collection<int, Transaction>
     */
    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'portefeuille')]
    private Collection $transactions;

    /**
     * @var Collection<int, Action>
     */
    #[ORM\ManyToMany(targetEntity: Action::class, mappedBy: 'portefeuilles')]
    private Collection $actions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
        $this->actions = new ArrayCollection();
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

    public function getProprietaire(): ?Trader
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?Trader $proprietaire): static
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setPortefeuille($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getPortefeuille() === $this) {
                $transaction->setPortefeuille(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Action>
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }

    public function addAction(Action $action): static
    {
        if (!$this->actions->contains($action)) {
            $this->actions->add($action);
            $action->addPortefeuille($this);
        }

        return $this;
    }

    public function removeAction(Action $action): static
    {
        if ($this->actions->removeElement($action)) {
            $action->removePortefeuille($this);
        }

        return $this;
    }
    /*
Explication de la méthode calculerValeurPortefeuille() :

La méthode initialise $valeurTotale à 0 et un tableau $quantites pour stocker les quantités nettes des actions.
Elle parcourt toutes les transactions du portefeuille pour calculer la quantité nette de chaque action :
Pour chaque transaction, elle récupère le symbole de l'action concernée.
Si le type de transaction est "achat", elle ajoute la quantité à $quantites.
Si le type est "vente", elle soustrait la quantité de $quantites.
Après avoir calculé les quantités nettes, elle parcourt les actions du portefeuille :
Pour chaque action, elle multiplie la quantité nette détenue par le prix actuel de l'action.
Elle ajoute cette valeur à $valeurTotale.
Enfin, elle retourne la valeur totale du portefeuille.
    */

    public function calculerValeurPortefeuille() {
        
        $valeurTotale = 0;
        foreach($this->getActions() as $action)
        {
            $coursActuel = $action->getPrix();
            $quantiteParAction = 0;
            foreach($action->getTransactions() as $transaction)
            {
                if($transaction->gettype()==="achat")
                {
                    $quantiteParAction += $transaction->getQuantite();
                }
                elseif($transaction->gettype()==="vente")
                {
                    $quantiteParAction -= $transaction->getQuantite();
                }
            }
            $valeurTotale +=  $coursActuel * $quantiteParAction;
        }
        return $valeurTotale;
    }

public  function nombreTotalActions(): int
{
    $total = 0;

    foreach($this->actions as $uneAction)
    {

    $total ++;
    }
    return $total;
}

public function obtenirSymboleActions(): array
{
$resultat = [];

foreach($this->actions as $symbole => $details)
{
    $resultat[] = $symbole;

}

return $resultat;
}


}
