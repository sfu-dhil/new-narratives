<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Place;
use App\Form\PlaceType;
use App\Repository\PlaceRepository;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/place")
 */
class PlaceController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @Route("/", name="place_index", methods={"GET"})
     *
     * @Template
     */
    public function index(Request $request, PlaceRepository $placeRepository) : array {
        $query = $placeRepository->indexQuery();
        $pageSize = (int) $this->getParameter('page_size');
        $page = $request->query->getint('page', 1);

        return [
            'places' => $this->paginator->paginate($query, $page, $pageSize),
        ];
    }

    /**
     * @Route("/search", name="place_search", methods={"GET"})
     *
     * @Template
     *
     * @return array
     */
    public function search(Request $request, PlaceRepository $placeRepository) {
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

    /**
     * @Route("/typeahead", name="place_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request, PlaceRepository $placeRepository) {
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

    /**
     * @Route("/new", name="place_new", methods={"GET", "POST"})
     * @Template
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new(Request $request) {
        $place = new Place();
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
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

    /**
     * @Route("/new_popup", name="place_new_popup", methods={"GET", "POST"})
     * @Template
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new_popup(Request $request) {
        return $this->new($request);
    }

    /**
     * @Route("/{id}", name="place_show", methods={"GET"})
     * @Template
     *
     * @return array
     */
    public function show(Place $place) {
        return [
            'place' => $place,
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="place_edit", methods={"GET", "POST"})
     *
     * @Template
     *
     * @return array|RedirectResponse
     */
    public function edit(Request $request, Place $place) {
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'The updated place has been saved.');

            return $this->redirectToRoute('place_show', ['id' => $place->getId()]);
        }

        return [
            'place' => $place,
            'form' => $form->createView(),
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}", name="place_delete", methods={"DELETE"})
     *
     * @return RedirectResponse
     */
    public function delete(Request $request, Place $place) {
        if ($this->isCsrfTokenValid('delete' . $place->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($place);
            $entityManager->flush();
            $this->addFlash('success', 'The place has been deleted.');
        }

        return $this->redirectToRoute('place_index');
    }
}
