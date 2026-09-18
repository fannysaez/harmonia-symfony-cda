<?php
namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\ArtistRepository;
use App\Repository\TrackRepository;
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
        TrackRepository $trackRepository
    ): Response {
        return $this->render('admin/index.html.twig', [
            'totalArtists' => count($artistRepository->findAll()),
            'totalAlbums'  => count($albumRepository->findAll()),
            'totalTracks'  => count($trackRepository->findAll()),
            'lastAlbums'   => $albumRepository->findLastAdded(5),
        ]);
    }
}
