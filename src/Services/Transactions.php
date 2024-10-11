<?php

namespace App\Services;
use App\Entity\Transaction;

class Transactions
{
    public function  calculerValeurAchats(array $transactions): float
    {
        $resultat = 0.00;
        foreach($transactions as $laTransaction)
        {
            if($laTransaction->gettype()==="achat")
            {
                $resultat+= $laTransaction->getPrix() * $laTransaction->getQuantite();
            }
        }
        return $resultat;
    }

    public function  calculerMoyennePrixVentes(array $transactions)
    {
        $resultat = 0.00;
        $cpt =0;
        foreach($transactions as $laTransaction)
        {
            if($laTransaction->gettype()==="vente")
            {
                $cpt++;
                $resultat+= $laTransaction->getPrix();
            }
        }

        return $resultat/$cpt;
    }
public function trouverTransactionQuantiteMax(array $transactions)
{
    $transactionMax = null;
    $QuantiteMax =0;
    foreach($transactions as $laTransaction)
    {
        if($laTransaction->getQuantite()>$QuantiteMax)
        {
            $QuantiteMax = $laTransaction->getQuantite();
            $transactionMax =  $laTransaction;
        }

    }
    return $transactionMax;
}

public function trouverTransactionQuantiteMaxV2(array $transactions)
{
    $transactionMax = null;

        // Vérifie si le tableau est vide
        if (empty($transactions)) {
            return null;
        }
    
        // Initialise avec la première transaction
        $transactionMax = $transactions[0];

    foreach($transactions as $laTransaction)
    {
        if($laTransaction->getQuantite() > $transactionMax->getQuantite())
        {
            $transactionMax = $laTransaction;
        }

    }
    return $transactionMax;
}

}