<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Place;
use App\Form\PlaceType;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/place')]
class PlaceController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    #[Route(path: '/', name: 'place_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, PlaceRepository $placeRepository) : array {
        $query = $placeRepository->indexQuery();
        $pageSize = (int) $this->getParameter('page_size');
        $page = $request->query->getInt('page', 1);

        return [
            'places' => $this->paginator->paginate($query, $page, $pageSize),
        ];
    }

    #[Route(path: '/search', name: 'place_search', methods: ['GET'])]
    #[Template]
    public function search(Request $request, PlaceRepository $placeRepository) : array {
        $q = $request->query->get('q');
        if ($q) {
            $query = $placeRepository->searchQuery($q);
            $places = $this->paginator->paginate($query, $request->query->getInt('page', 1), $this->getParameter('page_size'), ['wrap-queries' => true]);
        } else {
            $places = [];
        }

        return [
            'places' => $places,
            'q' => $q,
        ];
    }

    #[Route(path: '/typeahead', name: 'place_typeahead', methods: ['GET'])]
    public function typeahead(Request $request, PlaceRepository $placeRepository) : JsonResponse {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];

        foreach ($placeRepository->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string) $result,
            ];
        }

        return new JsonResponse($data);
    }

    #[Route(path: '/new', name: 'place_new', methods: ['GET', 'POST'])]
    #[Template]
    #[IsGranted('ROLE_CONTENT_ADMIN')]
    public function new(EntityManagerInterface $entityManager, Request $request) : array|RedirectResponse {
        $place = new Place();
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($place);
            $entityManager->flush();
            $this->addFlash('success', 'The new place has been saved.');

            return $this->redirectToRoute('place_show', ['id' => $place->getId()]);
        }

        return [
            'place' => $place,
            'form' => $form->createView(),
        ];
    }

    #[Route(path: '/{id}', name: 'place_show', methods: ['GET'])]
    #[Template]
    public function show(Place $place) : array {
        return [
            'place' => $place,
        ];
    }

    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Route(path: '/{id}/edit', name: 'place_edit', methods: ['GET', 'POST'])]
    #[Template]
    public function edit(EntityManagerInterface $entityManager, Request $request, Place $place) : array|RedirectResponse {
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'The updated place has been saved.');

            return $this->redirectToRoute('place_show', ['id' => $place->getId()]);
        }

        return [
            'place' => $place,
            'form' => $form->createView(),
        ];
    }

    #[IsGranted('ROLE_CONTENT_ADMIN')]
    #[Route(path: '/{id}', name: 'place_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, Request $request, Place $place) : RedirectResponse {
        if ($this->isCsrfTokenValid('delete' . $place->getId(), $request->request->get('_token'))) {
            $entityManager->remove($place);
            $entityManager->flush();
            $this->addFlash('success', 'The place has been deleted.');
        }

        return $this->redirectToRoute('place_index');
    }
}
