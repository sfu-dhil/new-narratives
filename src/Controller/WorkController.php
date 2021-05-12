<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Work;
use App\Form\WorkContributionsType;
use App\Form\WorkDatesType;
use App\Form\WorkSearchType;
use App\Form\WorkType;
use App\Repository\WorkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Work controller.
 *
 * @Route("/work")
 */
class WorkController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Work entities.
     *
     * @Route("/", name="work_index", methods={"GET"})
     *
     * @Template
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:Work e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $works = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'works' => $works,
        ];
    }

    /**
     * Full text search for Work entities.
     *
     * @Route("/search", name="work_search", methods={"GET"})
     *
     * @Template
     *
     * @return array
     */
    public function searchAction(Request $request, WorkRepository $repo) {
        $works = [];
        $form = $this->createForm(WorkSearchType::class, null, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $query = $repo->searchQuery($form->getData());

            $works = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        }

        return [
            'works' => $works,
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a new Work entity.
     *
     * @Route("/new", name="work_new", methods={"GET", "POST"})
     *
     * @Template
     * @Security("is_granted('ROLE_CONTENT_EDITOR')")
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
        $work = new Work();
        $form = $this->createForm(WorkType::class, $work);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($work);
            $em->flush();

            $this->addFlash('success', 'The new work was created.');

            return $this->redirectToRoute('work_show', ['id' => $work->getId()]);
        }

        return [
            'work' => $work,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Work entity.
     *
     * @Route("/{id}", name="work_show", methods={"GET"})
     *
     * @Template
     */
    public function showAction(Work $work) {
        return [
            'work' => $work,
        ];
    }

    /**
     * Displays a form to edit an existing Work entity.
     *
     * @Route("/{id}/edit", name="work_edit", methods={"GET", "POST"})
     *
     * @Template
     * @Security("is_granted('ROLE_CONTENT_EDITOR')")
     */
    public function editAction(Request $request, Work $work, EntityManagerInterface $em) {
        $editForm = $this->createForm(WorkType::class, $work);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The work has been updated.');

            return $this->redirectToRoute('work_show', ['id' => $work->getId()]);
        }

        return [
            'work' => $work,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Work entity.
     *
     * @Route("/{id}/delete", name="work_delete", methods={"GET"})
     *
     * @Security("is_granted('ROLE_CONTENT_EDITOR')")
     */
    public function deleteAction(Request $request, Work $work, EntityManagerInterface $em) {
        $em->remove($work);
        $em->flush();
        $this->addFlash('success', 'The work was deleted.');

        return $this->redirectToRoute('work_index');
    }

    /**
     * Add/remove dates to a work.
     *
     * @Route("/{id}/dates", name="work_dates")
     *
     * @Template
     * @Security("is_granted('ROLE_CONTENT_EDITOR')")
     */
    public function workDatesAction(Request $request, Work $work, EntityManagerInterface $em) {
        $form = $this->createForm(WorkDatesType::class, $work, [
            'work' => $work,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The dates have been updated.');

            return $this->redirectToRoute('work_show', ['id' => $work->getId()]);
        }

        return [
            'work' => $work,
            'form' => $form->createView(),
        ];
    }

    /**
     * Add contributions to a work.
     *
     * @Route("/{id}/contributions", name="work_contributions")
     *
     * @Template
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     */
    public function workContributionsAction(Request $request, Work $work, EntityManagerInterface $em) {
        $form = $this->createForm(WorkContributionsType::class, $work, [
            'work' => $work,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The contributions have been updated.');

            return $this->redirectToRoute('work_show', ['id' => $work->getId()]);
        }

        return [
            'work' => $work,
            'form' => $form->createView(),
        ];
    }
}
