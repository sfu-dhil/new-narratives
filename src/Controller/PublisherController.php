<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Repository\PublisherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/publisher')]
class PublisherController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    #[Route(path: '/', name: 'publisher_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, EntityManagerInterface $em) : array {
        $dql = 'SELECT e FROM App:Publisher e ORDER BY e.name';
        $query = $em->createQuery($dql);

        $publishers = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'publishers' => $publishers,
        ];
    }

    #[Route(path: '/search', name: 'publisher_search', methods: ['GET'])]
    #[Template]
    public function search(Request $request, PublisherRepository $repo) : array {
        $q = $request->query->get('q');
        if ($q) {
            $query = $repo->fulltextQuery($q);
            $publishers = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        } else {
            $publishers = [];
        }

        return [
            'publishers' => $publishers,
            'q' => $q,
        ];
    }

    #[Route(path: '/new', name: 'publisher_new', methods: ['GET', 'POST'])]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_EDITOR')")]
    public function new(Request $request, EntityManagerInterface $em) : array|RedirectResponse {
        $publisher = new Publisher();
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($publisher);
            $em->flush();

            $this->addFlash('success', 'The new publisher was created.');

            return $this->redirectToRoute('publisher_show', ['id' => $publisher->getId()]);
        }

        return [
            'publisher' => $publisher,
            'form' => $form->createView(),
        ];
    }

    #[Route(path: '/{id}', name: 'publisher_show', methods: ['GET'])]
    #[Template]
    public function show(Publisher $publisher) : array {
        return [
            'publisher' => $publisher,
        ];
    }

    #[Route(path: '/{id}/edit', name: 'publisher_edit', methods: ['GET', 'POST'])]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_EDITOR')")]
    public function edit(Request $request, Publisher $publisher, EntityManagerInterface $em) : array|RedirectResponse {
        $editForm = $this->createForm(PublisherType::class, $publisher);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The publisher has been updated.');

            return $this->redirectToRoute('publisher_show', ['id' => $publisher->getId()]);
        }

        return [
            'publisher' => $publisher,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Publisher entity.
     */
    #[Route(path: '/{id}/delete', name: 'publisher_delete', methods: ['GET'])]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    public function delete(Publisher $publisher, EntityManagerInterface $em) : RedirectResponse {
        $em->remove($publisher);
        $em->flush();
        $this->addFlash('success', 'The publisher was deleted.');

        return $this->redirectToRoute('publisher_index');
    }
}
