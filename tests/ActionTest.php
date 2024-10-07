<?php

namespace App\Tests;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Action;
use App\Entity\Portefeuille;
use App\Entity\Transaction;
use PHPUnit\Framework\TestCase;

class ActionTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }

    public function testCalculerQuantiteTotaleDansPortefeuilles()
    {
        // Création du mock de l'action (l'action testée)
        $actionMock = $this->getMockBuilder(Action::class)
                           ->disableOriginalConstructor()
                           ->getMock();

        // Création des mocks pour les transactions
        $transactionAchatMock = $this->createMock(Transaction::class);
        $transactionAchatMock->method('getType')->willReturn('achat');
        $transactionAchatMock->method('getQuantite')->willReturn(10);
        // La transaction renvoie la même instance d'Action que $actionMock
        $transactionAchatMock->method('getAction')->willReturn($actionMock);

        $transactionVenteMock = $this->createMock(Transaction::class);
        $transactionVenteMock->method('getType')->willReturn('vente');
        $transactionVenteMock->method('getQuantite')->willReturn(5);
        // La transaction renvoie la même instance d'Action que $actionMock
        $transactionVenteMock->method('getAction')->willReturn($actionMock);

        // Création d'une ArrayCollection pour les transactions
        $transactions = new ArrayCollection([$transactionAchatMock, $transactionVenteMock]);

        // Vérification intermédiaire : les transactions sont-elles bien ajoutées ?
        $this->assertCount(2, $transactions, "Les transactions n'ont pas été ajoutées correctement.");

        // Création du mock pour le portefeuille avec la collection de transactions
        $portefeuilleMock = $this->createMock(Portefeuille::class);
        $portefeuilleMock->method('getTransactions')->willReturn($transactions);

        // L'action possède une collection de portefeuilles avec des transactions
        $portefeuilleCollection = new ArrayCollection([$portefeuilleMock]);
        $this->assertCount(1, $portefeuilleCollection, "Le portefeuille n'a pas été ajouté correctement.");

        $actionMock->method('getPortefeuilles')
                   ->willReturn($portefeuilleCollection);

        // Appeler la méthode à tester
        $quantiteTotale = $actionMock->calculerQuantiteTotaleDansPortefeuilles();

        // Vérification finale : 10 (achat) - 5 (vente) = 5
        $this->assertEquals(5, $quantiteTotale, "Le calcul de la quantité totale est incorrect.");
    }
}
}
