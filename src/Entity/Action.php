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

    /**
     * @var Collection<int, Transaction>
     */
    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'action')]
    private Collection $transactions;

    public function __construct()
    {
        $this->portefeuilles = new ArrayCollection();
        $this->transactions = new ArrayCollection();
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


       
        //initialiser $quantiteTotale à 0
        //parcourir tous les portefeuillesqui contiennent cette action
        //pour chaque portefeuille, je parcours ses transactions 
        //je verifie que la transaction concerne l'action courante
        //si le type est "Achat", elle ajoute la quantité à $quantiteTotale
        //si le type est "Vente", elle soustrait la quantité à $quantiteTotale
        // enfin elle retourne la quantite totale de cette action détenue dans tous les portefeuilles
    
        public function calculerQuantiteTotaleDansPortefeuilles(): int
         {
            $quantiteTotale = 0;
    
            // Parcourt chaque portefeuille qui détient cette action
            foreach ($this->portefeuilles as $portefeuille) {
                // Parcourt les transactions du portefeuille
                foreach ($portefeuille->getTransactions() as $transaction) {
                    // Vérifie si la transaction concerne cette action
                    if ($transaction->getAction() === $this) {
                        if (strtolower($transaction->getType()) === 'achat') {
                            // Ajoute la quantité achetée
                            $quantiteTotale += $transaction->getQuantite();
                        } elseif (strtolower($transaction->getType()) === 'vente') {
                            // Soustrait la quantité vendue
                            $quantiteTotale -= $transaction->getQuantite();
                        }
                    }
                }
            }
    
            return $quantiteTotale;
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
                $transaction->setAction($this);
            }

            return $this;
        }

        public function removeTransaction(Transaction $transaction): static
        {
            if ($this->transactions->removeElement($transaction)) {
                // set the owning side to null (unless already changed)
                if ($transaction->getAction() === $this) {
                    $transaction->setAction(null);
                }
            }

            return $this;
        }

         /**
     * Liste les traders qui détiennent cette action.
     *
     * @return array Collection des traders détenant l'action.
     */
    public function listerTradersDetenantAction() {
        $traders = [];
        foreach ($this->portefeuilles as $portefeuille) {
            $trader = $portefeuille->getProprietaire();
            $nomTrader = $trader->getNom();
            if (!isset($traders[$nomTrader])) {
                $traders[$nomTrader] = $trader;
            }
        }
        return array_values($traders);
    }
    }

