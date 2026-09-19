<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\ArtistRepository;
use App\Repository\GenreRepository;
use App\Repository\AlbumRepository;
use App\Repository\PlaylistRepository;
use App\Repository\TrackRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_dashboard')]
    public function index(
        ArtistRepository $artistRepository,
        AlbumRepository $albumRepository,
        TrackRepository $trackRepository,
        PlaylistRepository $playlistRepository
    ): Response {
        return $this->render('admin/index.html.twig', [
            'totalArtists'   => count($artistRepository->findAll()),
            'totalAlbums'    => count($albumRepository->findAll()),
            'totalTracks'    => count($trackRepository->findAll()),
            'totalPlaylists' => count($playlistRepository->findAll()),
            'lastAlbums'     => $albumRepository->findLastAdded(5),
        ]);
    }

    #[Route('/admin/albums', name: 'app_admin_albums')]
    public function albums(AlbumRepository $albumRepository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 10;
        $total = count($albumRepository->findAll());
        $totalPages = (int) ceil($total / $limit);

        $albums = $albumRepository->createQueryBuilder('a')
            ->orderBy('a.title', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult(($page - 1) * $limit)
            ->getQuery()
            ->getResult();

        return $this->render('admin/albums/index.html.twig', [
            'albums'      => $albums,
            'page'        => $page,
            'totalPages'  => $totalPages,
        ]);
    }
    #[Route('/admin/tracks', name: 'app_admin_tracks')]
    public function tracks(TrackRepository $trackRepository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 10;
        $total = count($trackRepository->findAll());
        $totalPages = (int) ceil($total / $limit);

        $tracks = $trackRepository->createQueryBuilder('t')
            ->orderBy('t.title', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult(($page - 1) * $limit)
            ->getQuery()
            ->getResult();

        return $this->render('admin/tracks.html.twig', [
            'tracks'     => $tracks,
            'page'       => $page,
            'totalPages' => $totalPages,
        ]);
    }
    #[Route('/admin/playlists', name: 'app_admin_playlists')]
    public function playlists(PlaylistRepository $playlistRepository): Response
    {
        return $this->render('admin/playlists/index.html.twig', [
            'playlists' => $playlistRepository->findAll(),
        ]);
    }

    #[Route('/admin/artists', name: 'app_admin_artists')]
    public function artists(ArtistRepository $artistRepository): Response
    {
        return $this->render('admin/artists/index.html.twig', [
            'artists' => $artistRepository->findBy([], ['stageName' => 'ASC']),
        ]);
    }

    #[Route('/admin/genres', name: 'app_admin_genres')]
    public function genres(GenreRepository $genreRepository): Response
    {
        return $this->render('admin/genres/index.html.twig', [
            'genres' => $genreRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    #[Route('/admin/users', name: 'app_admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
}
