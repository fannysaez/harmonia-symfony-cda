<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(AlbumRepository $albumRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'albums'  => $albumRepository->findWithRelations('album'),
            'eps'     => $albumRepository->findWithRelations('ep'),
            'singles' => $albumRepository->findWithRelations('single'),
        ]);
    }
}
