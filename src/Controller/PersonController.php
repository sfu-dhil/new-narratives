<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\MediaBundle\Controller\ImageControllerTrait;
use Nines\MediaBundle\Entity\Image;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/person")
 */
class PersonController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    use ImageControllerTrait;

    /**
     * @Route("/", name="person_index", methods={"GET"})
     *
     * @Template(template="person/index.html.twig")
     */
    public function index(Request $request, PersonRepository $personRepository) : array {
        $query = $personRepository->indexQuery();
        $pageSize = (int) $this->getParameter('page_size');
        $page = $request->query->getint('page', 1);

        return [
            'people' => $this->paginator->paginate($query, $page, $pageSize),
        ];
    }

    /**
     * @Route("/search", name="person_search", methods={"GET"})
     *
     * @Template(template="person/search.html.twig")
     *
     * @return array
     */
    public function search(Request $request, PersonRepository $personRepository) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $personRepository->searchQuery($q);
            $people = $this->paginator->paginate($query, $request->query->getInt('page', 1), $this->getParameter('page_size'), ['wrap-queries' => true]);
        } else {
            $people = [];
        }

        return [
            'people' => $people,
            'q' => $q,
        ];
    }

    /**
     * @Route("/typeahead", name="person_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request, PersonRepository $personRepository) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];

        foreach ($personRepository->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string) $result,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/new", name="person_new", methods={"GET", "POST"})
     * @Template(template="person/new.html.twig")
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new(Request $request) {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($person);
            $entityManager->flush();

            $this->addFlash('success', 'The new person has been saved.');

            return $this->redirectToRoute('person_show', ['id' => $person->getId()]);
        }

        return [
            'person' => $person,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/new_popup", name="person_new_popup", methods={"GET", "POST"})
     * @Template(template="person/new_popup.html.twig")
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new_popup(Request $request) {
        return $this->new($request);
    }

    /**
     * @Route("/{id}", name="person_show", methods={"GET"})
     * @Template(template="person/show.html.twig")
     *
     * @return array
     */
    public function show(Person $person) {
        return [
            'person' => $person,
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="person_edit", methods={"GET", "POST"})
     *
     * @Template(template="person/edit.html.twig")
     *
     * @return array|RedirectResponse
     */
    public function edit(Request $request, Person $person) {
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'The updated person has been saved.');

            return $this->redirectToRoute('person_show', ['id' => $person->getId()]);
        }

        return [
            'person' => $person,
            'form' => $form->createView(),
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}", name="person_delete", methods={"DELETE"})
     *
     * @return RedirectResponse
     */
    public function delete(Request $request, Person $person) {
        if ($this->isCsrfTokenValid('delete' . $person->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($person);
            $entityManager->flush();
            $this->addFlash('success', 'The person has been deleted.');
        }

        return $this->redirectToRoute('person_index');
    }

    /**
     * @Route("/{id}/new_image", name="person_new_image", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @Template(template="@NinesMedia/image/new.html.twig")
     */
    public function newImage(Request $request, Person $person) {
        return $this->newImageAction($request, $person, 'person_show');
    }

    /**
     * @Route("/{id}/edit_image/{image_id}", name="person_edit_image", methods={"GET", "POST"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @ParamConverter("image", options={"id": "image_id"})
     *
     * @Template(template="@NinesMedia/image/edit.html.twig")
     */
    public function editImage(Request $request, Person $person, Image $image) {
        return $this->editImageAction($request, $person, $image, 'person_show');
    }

    /**
     * @Route("/{id}/delete_image/{image_id}", name="person_delete_image", methods={"DELETE"})
     * @ParamConverter("image", options={"id": "image_id"})
     * @IsGranted("ROLE_CONTENT_ADMIN")
     */
    public function deleteImage(Request $request, Person $person, Image $image) {
        return $this->deleteImageAction($request, $person, $image, 'person_show');
    }
}
