<?php

namespace App\Factory;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<Playlist>
 */
final class PlaylistFactory extends PersistentProxyObjectFactory
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
        return Playlist::class;
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
            'name' => self::faker()->sentence(2),
            'description' => self::faker()->optional(0.7)->paragraph(),
            'isPublic' => self::faker()->boolean(80), // 80% publiques, 20% privées (couvre le cas du brief)
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-1 year', 'now')),
            'user' => UserFactory::random(),
            // Associe entre 3 et 10 morceaux existants à la playlist
            'tracks' => TrackFactory::randomSet(self::faker()->numberBetween(3, 10)),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Playlist $playlist): void {})
        ;
    }
}
