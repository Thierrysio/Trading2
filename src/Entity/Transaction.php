<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use PhpParser\Node\Expr\Cast\Double;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    private ?Portefeuille $portefeuille = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    private ?Action $action = null;

    public function __construct($date) {
        // Validation du format de la date avant d'initialiser l'objet
        if (!$this->verifierFormatDate($date)) {
            throw new Exception("Le format de la date est invalide. La date doit être au format JJ/MM/AAAA.");
        }
            // Si la date est valide, initialisation des attributs
            $this->date = $date;

        }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

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

    public function getPortefeuille(): ?Portefeuille
    {
        return $this->portefeuille;
    }

    public function setPortefeuille(?Portefeuille $portefeuille): static
    {
        $this->portefeuille = $portefeuille;

        return $this;
    }

    public function calculerValeurTransaction() : float
    {
        //$resultat = 0;
        //$resultat = $this->getQuantite() * $this->getPrix();
        //return $resultat;
        return $this->quantite * $this->prix;
    }

    public function getAction(): ?Action
    {
        return $this->action;
    }

    public function setAction(?Action $action): static
    {
        $this->action = $action;

        return $this;
    }

    private function verifierFormatDate($date) {
        // Ajout des délimiteurs '/' autour du motif
        $pattern = '/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/(19|20)\d{2}$/';
        return preg_match($pattern, $date);
    }
public function estBeneficiaire(): bool
{
    $resultat = false;

    $prixActuel = $this->action->getPrix();

    if($this->type === "achat")
    {
        return $prixActuel > $this->prix;
    }else if ($this->type === "vente")
    {
        return $prixActuel < $this->prix;
    }


    return $resultat;
}
public function obtenirDateTransaction() :?date
{
    return $this->date;
}

}
