<?php

namespace App\Controller;

use App\Entity\Playlist;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PlaylistController extends AbstractController
{
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
    #[Route('/delete-playlist/{id}', name: 'app_delete_playlist', methods: ['POST'])]
    public function delete(Playlist $playlist, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if ($playlist->getUser() !== $user) {
            throw $this->createAccessDeniedException();
        }

        $em->remove($playlist);
        $em->flush();

        return $this->redirectToRoute('app_profil');
    }
}
