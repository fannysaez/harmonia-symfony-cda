<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class ArtistControllerTest extends WebTestCase
{
    public function testArtistesPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/artist');

        $this->assertResponseIsSuccessful();
    }
    public function testArtistShowPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/artist/21');
        $this->assertResponseIsSuccessful();
    }

    public function testArtistAddPageWhenLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('fanny.saez.0486@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/artist-add');
        $this->assertResponseIsSuccessful();
    }
}
