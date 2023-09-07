<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
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
    }

    public function testCreateSerieIsWorkingIfUserLogged(){
        $client = static::createClient();

        //Récupérer le mega container des services Symfony
        $userRepository = static::getContainer()->get(UserRepository::class); // Permet de récupérer un repository (ca pourrait être n'importe quel service)
        $user = $userRepository->findOneBy(["email"=>'d-m@live.fr']); // Le findOneBy() attends un tableau de Where. Le findBy() attend uniquement un id.

        $client->loginUser($user); // On a donc le mail, et le mdp qui est deja crypté. En réalité on ne passe pas réellement par le fomrumaire de login. On ne fait que comparer les mdp deja hasché
        $crawler = $client->request('GET', '/serie/new');

        $this->assertResponseIsSuccessful();

    }

    public function testAccountCreation(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $client->submitForm("Register", [
            "registration_form[email]" => "testz@unit.fr",
            "registration_form[lastname]" => "Unit",
            "registration_form[firstname]" => "Test",
            "registration_form[plainPassword]" => "azerty",
            "registration_form[agreeTerms]" => true
        ]);

        $this->assertResponseRedirects("/serie/list", 302);
    }
}
