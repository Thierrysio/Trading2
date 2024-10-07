<?php

namespace App\Tests;

use App\Entity\Action;
use App\Entity\Portefeuille;
use App\Entity\Transaction;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class PortefeuilleTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }
    public function testCalculerValeurPortefeuille()
    {
        // Création des mocks pour les actions
        $actionMock1 = $this->createMock(Action::class);
        $actionMock1->method('getSymbole')->willReturn('AAPL');
        $actionMock1->method('getPrix')->willReturn(150.0);

        $actionMock2 = $this->createMock(Action::class);
        $actionMock2->method('getSymbole')->willReturn('GOOGL');
        $actionMock2->method('getPrix')->willReturn(2800.0);

        // Création des mocks pour les transactions associées aux actions
        $transactionAchatMock1 = $this->createMock(Transaction::class);
        $transactionAchatMock1->method('getAction')->willReturn($actionMock1);
        $transactionAchatMock1->method('getQuantite')->willReturn(10);
        $transactionAchatMock1->method('getType')->willReturn('achat');

        $transactionVenteMock1 = $this->createMock(Transaction::class);
        $transactionVenteMock1->method('getAction')->willReturn($actionMock1);
        $transactionVenteMock1->method('getQuantite')->willReturn(3);
        $transactionVenteMock1->method('getType')->willReturn('vente');

        $transactionAchatMock2 = $this->createMock(Transaction::class);
        $transactionAchatMock2->method('getAction')->willReturn($actionMock2);
        $transactionAchatMock2->method('getQuantite')->willReturn(5);
        $transactionAchatMock2->method('getType')->willReturn('achat');

        // Création d'une collection de transactions
        $transactions = new ArrayCollection([
            $transactionAchatMock1,
            $transactionVenteMock1,
            $transactionAchatMock2
        ]);

        // Création d'une collection d'actions
        $actions = new ArrayCollection([$actionMock1, $actionMock2]);

        // Création du mock pour le portefeuille
        $portefeuilleMock = $this->getMockBuilder(Portefeuille::class)
                                 ->disableOriginalConstructor()
                                 ->getMock();

        // Configuration des méthodes pour retourner les collections
        $portefeuilleMock->method('getActions')->willReturn($actions);
        $portefeuilleMock->method('getTransactions')->willReturn($transactions);

        // Appel de la méthode à tester
        $valeurTotale = $portefeuilleMock->calculerValeurPortefeuille();

        // Vérification des résultats attendus
        // Pour AAPL : (10 - 3) * 150 = 7 * 150 = 1050
        // Pour GOOGL : 5 * 2800 = 14000
        // Valeur totale = 1050 + 14000 = 15050
        $this->assertEquals(15050, $valeurTotale, "Le calcul de la valeur totale du portefeuille est incorrect.");
    }
}
