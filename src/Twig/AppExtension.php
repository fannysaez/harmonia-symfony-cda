<?php

namespace App\Twig;

use App\Repository\GenreRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(private GenreRepository $genreRepository) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getGenres', [$this, 'getGenres']),
        ];
    }

    public function getGenres(): array
    {
        return $this->genreRepository->findBy([], ['name' => 'ASC']);
    }
}
