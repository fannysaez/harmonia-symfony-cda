<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class PlaylistControllerTest extends WebTestCase
{
    public function testPlaylistsPageRedirectsToLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $this->assertResponseRedirects('/login');
    }
    public function testPlaylistsPageWhenLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('fanny.saez.0486@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/playlists');
        $this->assertResponseIsSuccessful();
    }
}
