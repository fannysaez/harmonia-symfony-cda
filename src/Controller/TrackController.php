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

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/edit-track/{id}', name: 'app_track_edit')]
    public function edit(Track $track, EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(TrackType::class, $track);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_album_show', ['id' => $track->getAlbum()->getId()]);
        }

        return $this->render('track/edit.html.twig', [
            'form' => $form->createView(),
            'track' => $track,
            'album' => $track->getAlbum(),
        ]);
    }

#[Route('/track/{id}/listen', name: 'app_track_listen', methods: ['POST'])]
public function listen(Track $track, EntityManagerInterface $em, Request $request): Response
{
    if ($this->isCsrfTokenValid('listen' . $track->getId(), $request->request->get('_token'))) {
        $track->setListenCount($track->getListenCount() + 1);

        $user = $this->getUser();
        if ($user) {
            $history = new \App\Entity\ListeningHistory();
            $history->setTrack($track);
            $history->setUser($user);
            $history->setListenedAt(new \DateTimeImmutable());
            $history->setCreatedAt(new \DateTimeImmutable());
            $em->persist($history);
        }

        $em->flush();
    }

    return $this->redirectToRoute('app_album_show', ['id' => $track->getAlbum()->getId()]);
}
}
