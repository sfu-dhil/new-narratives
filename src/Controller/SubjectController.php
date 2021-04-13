<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Subject;
use App\Form\SubjectSearchType;
use App\Form\SubjectType;
use App\Repository\SubjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Subject controller.
 *
 * @Route("/subject")
 */
class SubjectController extends AbstractController implements PaginatorAwareInterface
{
    use PaginatorTrait;

    /**
     * Lists all Subject entities.
     *
     * @Route("/", name="subject_index", methods={"GET"})
     *
     * @Template
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:Subject e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $subjects = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'subjects' => $subjects,
        ];
    }

    /**
     * Search for Subject entities.
     *
     * @Route("/search", name="subject_search", methods={"GET"})
     *
     * @Template
     */
    public function searchAction(Request $request, SubjectRepository $repo) {
        $form = $this->createForm(SubjectSearchType::class);
        $subjects = [];
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $query = $repo->searchQuery($form->getData());
            $subjects = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        }

        return [
            'search_form' => $form->createView(),
            'subjects' => $subjects,
        ];
    }

    /**
     * Creates a new Subject entity.
     *
     * @Route("/new", name="subject_new", methods={"GET", "POST"})
     *
     * @Template
     * @Security("is_granted('ROLE_CONTENT_EDITOR')")
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
        $subject = new Subject();
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($subject);
            $em->flush();

            $this->addFlash('success', 'The new subject was created.');

            return $this->redirectToRoute('subject_show', ['id' => $subject->getId()]);
        }

        return [
            'subject' => $subject,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Subject entity.
     *
     * @Route("/{id}", name="subject_show", methods={"GET"})
     *
     * @Template
     */
    public function showAction(Subject $subject) {
        return [
            'subject' => $subject,
        ];
    }

    /**
     * Displays a form to edit an existing Subject entity.
     *
     * @Route("/{id}/edit", name="subject_edit", methods={"GET", "POST"})
     *
     * @Template
     * @Security("is_granted('ROLE_CONTENT_EDITOR')")
     */
    public function editAction(Request $request, Subject $subject, EntityManagerInterface $em) {
        $editForm = $this->createForm(SubjectType::class, $subject);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The subject has been updated.');

            return $this->redirectToRoute('subject_show', ['id' => $subject->getId()]);
        }

        return [
            'subject' => $subject,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Subject entity.
     *
     * @Route("/{id}/delete", name="subject_delete", methods={"GET"})
     *
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     */
    public function deleteAction(Request $request, Subject $subject, EntityManagerInterface $em) {
        $em->remove($subject);
        $em->flush();
        $this->addFlash('success', 'The subject was deleted.');

        return $this->redirectToRoute('subject_index');
    }
}
