<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Factory\ArtistFactory;
use App\Factory\GenreFactory;
use App\Factory\UserFactory;
use App\Factory\AlbumFactory;
use App\Factory\TrackFactory;
use App\Factory\PlaylistFactory;
use App\Factory\ListeningHistoryFactory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        // Niveau 1 : Indépendants
        ArtistFactory::createMany(10);
        GenreFactory::createMany(8);

        // Niveau 2 : Albums
        AlbumFactory::createMany(15);
        // Niveau 3 : Morceaux
        TrackFactory::createMany(60);
        // Niveau 1 bis : Utilisateurs (placé ici pour piocher dans les morceaux déjà créés)
        UserFactory::createMany(20);

        // Niveau 4 : Playlists et Historique d'écoutes
        PlaylistFactory::createMany(12);
        ListeningHistoryFactory::createMany(100);

        $manager->flush();
    }
}
