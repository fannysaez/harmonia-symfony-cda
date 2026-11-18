<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class ProfilControllerTest extends WebTestCase
{
    public function testProfilRedirectsToLogin(): void // mon KO
    {
        $client = static::createClient();
        $client->request('GET', '/profil');
        $this->assertResponseRedirects('/login'); // 302 correct
    }

    public function testProfilPageWhenLoggedIn(): void // mon OK
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('fanny.saez.0486@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/profil');
        $this->assertResponseIsSuccessful(); // 200 correct
        // Vérifie que c'est exactement 200
        $this->assertResponseStatusCodeSame(200);
    }

    public function testProfilEditPageWhenLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('fanny.saez.0486@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/profil/edit');
        $this->assertResponseIsSuccessful();
    }
}
