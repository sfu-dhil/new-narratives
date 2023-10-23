<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/genre')]
class GenreController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    #[Route(path: '/', name: 'genre_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, EntityManagerInterface $em) : array {
        $dql = 'SELECT e FROM App:Genre e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $genres = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'genres' => $genres,
        ];
    }

    #[Route(path: '/new', name: 'genre_new', methods: ['GET', 'POST'])]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_EDITOR')")]
    public function new(Request $request, EntityManagerInterface $em) : array|RedirectResponse {
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($genre);
            $em->flush();

            $this->addFlash('success', 'The new genre was created.');

            return $this->redirectToRoute('genre_show', ['id' => $genre->getId()]);
        }

        return [
            'genre' => $genre,
            'form' => $form->createView(),
        ];
    }

    #[Route(path: '/{id}', name: 'genre_show', methods: ['GET'])]
    #[Template]
    public function show(Genre $genre) : array {
        return [
            'genre' => $genre,
        ];
    }

    #[Route(path: '/{id}/edit', name: 'genre_edit', methods: ['GET', 'POST'])]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_EDITOR')")]
    public function edit(Request $request, Genre $genre, EntityManagerInterface $em) : array|RedirectResponse {
        $editForm = $this->createForm(GenreType::class, $genre);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The genre has been updated.');

            return $this->redirectToRoute('genre_show', ['id' => $genre->getId()]);
        }

        return [
            'genre' => $genre,
            'edit_form' => $editForm->createView(),
        ];
    }

    #[Route(path: '/{id}/delete', name: 'genre_delete', methods: ['GET'])]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    public function delete(Genre $genre, EntityManagerInterface $em) : RedirectResponse {
        $em->remove($genre);
        $em->flush();
        $this->addFlash('success', 'The genre was deleted.');

        return $this->redirectToRoute('genre_index');
    }
}
