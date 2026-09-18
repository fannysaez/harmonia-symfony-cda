<?php
namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class GenreController extends AbstractController
{
    #[Route('/admin/genres', name: 'app_genre_list')]
    public function list(GenreRepository $repo): Response
    {
        return $this->render('genre/list.html.twig', ['genres' => $repo->findAll()]);
    }

    #[Route('/admin/genre/add', name: 'app_genre_add')]
    public function add(EntityManagerInterface $em, Request $request): Response
    {
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $genre->setCreatedAt(new \DateTimeImmutable());
            $em->persist($genre);
            $em->flush();
            return $this->redirectToRoute('app_genre_list');
        }
        return $this->render('genre/add.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/admin/genre/{id}/edit', name: 'app_genre_edit')]
    public function edit(Genre $genre, EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_genre_list');
        }
        return $this->render('genre/edit.html.twig', ['form' => $form->createView(), 'genre' => $genre]);
    }

    #[Route('/admin/genre/{id}/remove', name: 'app_genre_remove', methods: ['POST'])]
    public function remove(Genre $genre, EntityManagerInterface $em, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $genre->getId(), $request->request->get('_token'))) {
            $em->remove($genre);
            $em->flush();
        }
        return $this->redirectToRoute('app_genre_list');
    }
}
