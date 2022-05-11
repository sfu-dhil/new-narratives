<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Publisher controller.
 *
 * @Route("/publisher")
 */
class PublisherController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Publisher entities.
     *
     * @Route("/", name="publisher_index", methods={"GET"})
     *
     * @Template
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:Publisher e ORDER BY e.name';
        $query = $em->createQuery($dql);

        $publishers = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'publishers' => $publishers,
        ];
    }

    /**
     * Full text search for Publisher entities.
     *
     * @Route("/search", name="publisher_search", methods={"GET"})
     *
     * @Template
     *
     * @return array
     */
    public function searchAction(Request $request, PublisherRepository $repo) {
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

    /**
     * Creates a new Publisher entity.
     *
     * @Route("/new", name="publisher_new", methods={"GET", "POST"})
     *
     * @Template
     * @Security("is_granted('ROLE_CONTENT_EDITOR')")
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
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

    /**
     * Finds and displays a Publisher entity.
     *
     * @Route("/{id}", name="publisher_show", methods={"GET"})
     *
     * @Template
     */
    public function showAction(Publisher $publisher) {
        return [
            'publisher' => $publisher,
        ];
    }

    /**
     * Displays a form to edit an existing Publisher entity.
     *
     * @Route("/{id}/edit", name="publisher_edit", methods={"GET", "POST"})
     *
     * @Template
     * @Security("is_granted('ROLE_CONTENT_EDITOR')")
     */
    public function editAction(Request $request, Publisher $publisher, EntityManagerInterface $em) {
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
     *
     * @Route("/{id}/delete", name="publisher_delete", methods={"GET"})
     *
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     */
    public function deleteAction(Request $request, Publisher $publisher, EntityManagerInterface $em) {
        $em->remove($publisher);
        $em->flush();
        $this->addFlash('success', 'The publisher was deleted.');

        return $this->redirectToRoute('publisher_index');
    }
}
