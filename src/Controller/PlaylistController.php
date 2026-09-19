<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Entity\Track;
use App\Form\PlaylistType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PlaylistController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/playlists', name: 'app_playlist_index')]
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('playlist/index.html.twig', [
            'playlists' => $em->getRepository(Playlist::class)->findBy([
                'user' => $this->getUser(),
            ]),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/create-playlist', name: 'app_create_playlist', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }

        $playlist = new Playlist();
        $playlist->setName($request->request->get('name', 'Ma playlist'));
        $playlist->setDescription($request->request->get('description'));
        $playlist->setUser($user);
        $playlist->setIsPublic($request->request->has('isPublic'));
        $playlist->setCreatedAt(new \DateTimeImmutable());

        $em->persist($playlist);
        $em->flush();

        return $this->redirectToRoute('app_profil');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/playlist/{id}/add-track/{track_id}', name: 'app_playlist_add_track', methods: ['POST'])]
    public function addTrack(Playlist $playlist, int $track_id, EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();
        if ($playlist->getUser() !== $user) {
            throw $this->createAccessDeniedException();
        }

        $track = $em->getRepository(Track::class)->find($track_id);
        if ($track && !$playlist->getTracks()->contains($track)) {
            $playlist->addTrack($track);
            $em->flush();
        }

        return $this->redirectToRoute('app_album_show', ['id' => $track->getAlbum()->getId()]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/playlist/{id}/edit', name: 'app_playlist_edit', methods: ['GET', 'POST'])]
    public function edit(Playlist $playlist, Request $request, EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if ($playlist->getUser() !== $user) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Playlist modifiée !');
            return $this->redirectToRoute('app_profil');
        }

        return $this->render('playlist/edit.html.twig', [
            'form' => $form->createView(),
            'playlist' => $playlist,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/delete-playlist/{id}', name: 'app_delete_playlist', methods: ['POST'])]
    public function delete(Playlist $playlist, EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if ($playlist->getUser() !== $user) {
            throw $this->createAccessDeniedException();
        }

        $em->remove($playlist);
        $em->flush();

        return $this->redirectToRoute('app_profil');
    }
}
