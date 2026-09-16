<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Album;

final class AlbumController extends AbstractController
{
    #[Route('/album', name: 'app_albums')]
    public function index(AlbumRepository $albumRepository): Response
    {
        $albums = $albumRepository->findBy(['type' => 'album']);
        $EP = $albumRepository->findBy(['type' => 'EP']);
        $singles = $albumRepository->findBy(['type' => 'single']);

        return $this->render('album/albums.html.twig', [
            'albums' => $albums,
            'EP' => $EP,
            'singles' => $singles,
        ]);
    }

     #[Route('/album/{id}', name: 'app_album_show')]
    public function show(Album $album): Response
    {
        return $this->render('album/show.html.twig', [
            'album' => $album,
        ]);
    }
}
