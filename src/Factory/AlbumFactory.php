<?php

namespace App\Factory;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<Album>
 */
final class AlbumFactory extends PersistentProxyObjectFactory
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
        return Album::class;
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
            'artist' => ArtistFactory::random(),
            'coverImage' => 'album' . self::faker()->numberBetween(1, 10) . '.jpg',
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-2 years', 'now')),
            'releaseDate' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-10 years', 'now')),
            'title' => self::faker()->sentence(3),
            'type' => self::faker()->randomElement(['album', 'EP', 'single']),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Album $album): void {})
        ;
    }
}
