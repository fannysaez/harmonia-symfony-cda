<?php

namespace App\Controller;

use App\Entity\Track;
use App\Form\TrackType;
use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class TrackController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/add-track/{album_id}', name: 'app_add_track')]
    public function add(int $album_id, AlbumRepository $albumRepository, EntityManagerInterface $em, Request $request): Response
    {
        $album = $albumRepository->find($album_id);
        if ($album === null) {
            return $this->redirectToRoute('app_albums');
        }

        $track = new Track();
        $track->setAlbum($album);

        $form = $this->createForm(TrackType::class, $track);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $track->setCreatedAt(new \DateTimeImmutable());
            $track->setListenCount(0);
            $em->persist($track);
            $em->flush();
            return $this->redirectToRoute('app_album_show', ['id' => $album->getId()]);
        }

        return $this->render('track/add.html.twig', [
            'form' => $form->createView(),
            'album' => $album,
        ]);
    }
}
