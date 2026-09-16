<?php

namespace App\Factory;

use App\Entity\Track;
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<Track>
 */
final class TrackFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct() {}

    #[\Override]
    public static function class(): string
    {
        return Track::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'title' => self::faker()->sentence(2),
            'duration' => self::faker()->numberBetween(90, 360), // Durée en secondes (1m30 à 6m)
            'trackNumber' => self::faker()->numberBetween(1, 15),
            'listenCount' => self::faker()->numberBetween(0, 5000),
            'isExplicit' => self::faker()->boolean(20), // 20% de chance d'être explicite
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-2 years', 'now')),
            'album' => AlbumFactory::random(),
            // Relation ManyToMany : attribue 1 à 2 genres existants par morceau
            'genres' => GenreFactory::randomSet(self::faker()->numberBetween(1, 2)),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Track $track): void {})
        ;
    }
}
