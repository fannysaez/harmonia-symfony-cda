<?php

namespace App\Factory;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<Genre>
 */
final class GenreFactory extends PersistentProxyObjectFactory
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
        return Genre::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        // Variable PHP simple : sert uniquement de liste d'options pour Faker
        $genres = [
            'rock', 'reggae', 'classique', 'jazz', 'pop', 'hip-hop',
            'electro', 'blues', 'metal', 'funk', 'soul', 'country',
            'folk', 'punk', 'disco', 'rnb', 'rap', 'house',
            'techno', 'ambient', 'salsa', 'bossa nova', 'ska',
            'gospel', 'grime', 'dubstep', 'afrobeats', 'drill', 'synthwave'
        ];

        return [
            'color' => self::faker()->hexColor(),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-2 years', 'now')),
            'name' => self::faker()->unique()->randomElement($genres),
            ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Genre $genre): void {})
        ;
    }
}
