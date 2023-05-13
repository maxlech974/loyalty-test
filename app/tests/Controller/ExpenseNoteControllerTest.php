<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;

/**
 * Class ExpenseNoteControllerTest
 *
 * Classe de tests pour le contrôleur ExpenseNoteController
 *
 * @package App\Tests\Controller
 */
class ExpenseNoteControllerTest extends WebTestCase
{  
    private const PREFIX_URL = '/api/expense';

    /**
     * @var \Symfony\Bundle\FrameworkBundle\KernelBrowser
     */
    private $client;

    /**
     * @var \App\Entity\ExpenseNote
     */
    private $expenseNote;

    private $token;

    /**
     * Préparation de l'environnement de test
     */
    protected function setUp(): void
    {
        $this->loadFixtures();
        
        $this->client = static::createClient();
        $this->expenseNote = $this->client->getContainer()
        ->get('doctrine')
        ->getRepository(\App\Entity\ExpenseNote::class)
        ->findOneBy([]);
        
        $this->logUser();
    }
    
    /**
     * Chargement des fixtures avant chaque test
     *
     * @throws \RuntimeException si une erreur se produit pendant le chargement des fixtures
     */
    protected function loadFixtures(): void
    {
        $process = new Process(['bin/console', 'hautelook:fixtures:load', '--no-interaction']);
        $process->run();
        
        if (!$process->isSuccessful()) {
            throw new \RuntimeException(sprintf('Error loading fixtures: %s', $process->getErrorOutput()));
        }
    }
    
    public function logUser()
    {
        $credentials = [
            'email' => 'maximelechere.dev@gmail.com',
            'password' => 'test',
        ];

        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($credentials)
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseContent = $this->client->getResponse()->getContent();
        $this->assertJson($responseContent);
        $responseData = json_decode($responseContent, true);
        $this->token = $responseData['token'];
        $this->assertArrayHasKey('token', $responseData);
    }

    /**
     * Test de la création d'une note de frais via une requête POST
     */
    public function testPostExpenseNote(): void
    {

        $this->createAuthenticatedRequest('POST', self::PREFIX_URL, [
            'noteDate' => '10/05/2023',
            'amount' => '99.33',
            'type' => 'restaurant',
            'companyName' => 'test'
        ]);

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $responseContent = $this->client->getResponse()->getContent();
        $this->assertJson($responseContent);
        $responseData = json_decode($responseContent, true);

        $this->assertArrayHasKey('id', $responseData);
    }

    /**
     * Test de la mise à jour d'une note de frais via une requête PUT
     */
    public function testPutExpenseNote(): void
    {

        $this->createAuthenticatedRequest('PUT', self::PREFIX_URL . '/' . $this->expenseNote->getId(), [
            'noteDate' => '10/05/2023',
            'amount' => '50.46',
            'type' => 'hotel',
            'companyName' => 'test'
        ]);
    
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseContent = $this->client->getResponse()->getContent();
        $this->assertJson($responseContent);
        $responseData = json_decode($responseContent, true);
        $this->assertEquals("50.46", $responseData['amount']);
        $this->assertEquals("hotel", $responseData['type']);
    }

    /**
     * Test de la récupération de toutes les notes de frais via une requête GET
     */
    public function testGetAllExpenseNotes(): void
    {
        $this->createAuthenticatedRequest('GET', self::PREFIX_URL);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }


    /**
     * Test de la récupération d'une note de frais par son ID via une requête GET
     */
    public function testGetExpenseNoteById():void
    {    
        $this->createAuthenticatedRequest('GET', self::PREFIX_URL . '/' . $this->expenseNote->getId());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test de la suppression d'une note de frais par son ID via une requête DELETE
     */
    public function testDeleteExpenseNote():void
    {
        $this->createAuthenticatedRequest('DELETE', self::PREFIX_URL . '/' . $this->expenseNote->getId());

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Crée une requête authentifiée pour le client de test.
     *
     * @param string $method La méthode HTTP pour la requête (ex : 'POST', 'GET', 'PUT', etc.).
     * @param string $url L'URL de la requête.
     * @param array $content Le corps de la requête, qui sera encodé en JSON.
     *
     * @return void
     */
    private function createAuthenticatedRequest(string $method, string $url, array $content = []): void
    {
        $this->client->request(
            $method, 
            $url, 
            [], 
            [], 
            [
                'CONTENT_TYPE' => 'application/json', 
                'HTTP_Authorization' => 'Bearer ' . $this->token
            ], 
            json_encode($content)
        );
    }
}