<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testHomePage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/home');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', "Serie's detail");
    }

    public function testCreateSerieWorkingIfNotLogged(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/serie/add');

        $this->assertResponseRedirects('/login', 302);

    }

    public function testCreateSerieWorkingIfLogged(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'luca.dupont2022@campus-eni.fr']);

        $client->loginUser($user);

        $crawler = $client->request('GET', '/serie/add');

        $this->assertResponseIsSuccessful();

    }
}
