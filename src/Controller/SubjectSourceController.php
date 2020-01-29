<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\SubjectSource;
use App\Form\SubjectSourceType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * SubjectSource controller.
 *
 * @Route("/subject_source")
 */
class SubjectSourceController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all SubjectSource entities.
     *
     * @Route("/", name="subject_source_index", methods={"GET"})
     *
     * @Template()
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:SubjectSource e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $subjectSources = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'subjectSources' => $subjectSources,
        ];
    }

    /**
     * Creates a new SubjectSource entity.
     *
     * @Route("/new", name="subject_source_new", methods={"GET","POST"})
     *
     * @Template()
     * @Security("is_granted('ROLE_CONTENT_EDITOR')")
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
        $subjectSource = new SubjectSource();
        $form = $this->createForm(SubjectSourceType::class, $subjectSource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($subjectSource);
            $em->flush();

            $this->addFlash('success', 'The new subjectSource was created.');

            return $this->redirectToRoute('subject_source_show', ['id' => $subjectSource->getId()]);
        }

        return [
            'subjectSource' => $subjectSource,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a SubjectSource entity.
     *
     * @Route("/{id}", name="subject_source_show", methods={"GET"})
     *
     * @Template()
     */
    public function showAction(SubjectSource $subjectSource) {
        return [
            'subjectSource' => $subjectSource,
        ];
    }

    /**
     * Displays a form to edit an existing SubjectSource entity.
     *
     * @Route("/{id}/edit", name="subject_source_edit", methods={"GET","POST"})
     *
     * @Template()
     * @Security("is_granted('ROLE_CONTENT_EDITOR')")
     */
    public function editAction(Request $request, SubjectSource $subjectSource, EntityManagerInterface $em) {
        $editForm = $this->createForm(SubjectSourceType::class, $subjectSource);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The subjectSource has been updated.');

            return $this->redirectToRoute('subject_source_show', ['id' => $subjectSource->getId()]);
        }

        return [
            'subjectSource' => $subjectSource,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a SubjectSource entity.
     *
     * @Route("/{id}/delete", name="subject_source_delete", methods={"GET"})
     *
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     */
    public function deleteAction(Request $request, SubjectSource $subjectSource, EntityManagerInterface $em) {
        $em->remove($subjectSource);
        $em->flush();
        $this->addFlash('success', 'The subjectSource was deleted.');

        return $this->redirectToRoute('subject_source_index');
    }
}
