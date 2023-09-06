<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();  // Simule un navigateur (un client)
        $crawler = $client->request('GET', '/'); // qui va se connecter sur cette url

        $this->assertResponseIsSuccessful(); // Renvoie un code 200 (Success )
        $this->assertSelectorTextContains('h1', 'Welcome in Serie !'); //voir home.html.twig
    }

    public function testCreateSerieIsWorkingIfUserNotLogged(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/serie/new'); // il faut etre connecté pour réussir à aller sur cette page. Pour l'instant ce n'est pas le cas.

        $this->assertResponseRedirects("/login", 302); // Renvoie un code 200 (Success )
//        $this->assertSelectorTextContains('h2', 'Ajouter une nouvelle série');
    }
}
