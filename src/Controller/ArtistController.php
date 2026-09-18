<?php
namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ArtistController extends AbstractController
{
    #[Route('/artist', name: 'app_artist_list')]
    public function list(ArtistRepository $artistRepository): Response
    {
        $artists = $artistRepository->findAll();
        return $this->render('artist/list.html.twig', [
            'artists' => $artists,
        ]);
    }

    #[Route('/artist/{id}', name: 'app_artist_show')]
    public function show(int $id, ArtistRepository $artistRepository): Response
    {
        $artist = $artistRepository->find($id);
        if ($artist === null) {
            return $this->redirectToRoute('app_artist_list');
        }
        return $this->render('artist/show.html.twig', [
            'artist' => $artist,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/artist-add', name: 'app_artist_add')]
    public function add(EntityManagerInterface $em, Request $request): Response
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $artist->setCreatedAt(new \DateTimeImmutable());
            $em->persist($artist);
            $em->flush();
            return $this->redirectToRoute('app_artist_list');
        }
        return $this->render('artist/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/artist-edit/{id}', name: 'app_artist_edit')]
    public function edit(int $id, ArtistRepository $artistRepository, EntityManagerInterface $em, Request $request): Response
    {
        $artist = $artistRepository->find($id);
        if ($artist === null) {
            return $this->redirectToRoute('app_artist_list');
        }
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_artist_list');
        }
        return $this->render('artist/edit.html.twig', [
            'form' => $form->createView(),
            'artist' => $artist,
        ]);
    }
}
