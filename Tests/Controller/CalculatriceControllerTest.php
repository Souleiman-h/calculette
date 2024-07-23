<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CalculatriceControllerTest extends WebTestCase
{
    public function testAddition()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/calculatrice', [
            'nombre1' => 10,
            'nombre2' => 5,
            'operation' => 'addition'
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('p', '15');
    }
}