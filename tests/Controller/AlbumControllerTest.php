<?php

namespace App\Tests\Controller;

use App\Repository\AlbumRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AlbumControllerTest extends WebTestCase
{
    public function testAlbumListPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/album');
        $this->assertResponseIsSuccessful();
    }

    public function testAlbumShowPage(): void
    {
        $client = static::createClient();
        $albumRepository = static::getContainer()->get(AlbumRepository::class);
        $album = $albumRepository->findOneBy([]);

        $client->request('GET', '/album/'.$album->getId());
        $this->assertResponseIsSuccessful();
    }

    public function testAlbumAddPageWhenLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('fanny.saez.0486@gmail.com');
        $client->loginUser($user);

        $client->request('GET', '/album-add');
        $this->assertResponseIsSuccessful();
    }
}
