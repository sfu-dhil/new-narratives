<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SubjectSource;
use AppBundle\Form\SubjectSourceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * SubjectSource controller.
 *
 * @Route("/subject_source")
 */
class SubjectSourceController extends Controller {

    /**
     * Lists all SubjectSource entities.
     *
     * @Route("/", name="subject_source_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:SubjectSource e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $subjectSources = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'subjectSources' => $subjectSources,
        );
    }

    /**
     * Creates a new SubjectSource entity.
     *
     * @Route("/new", name="subject_source_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * 
     * @param Request $request
     */
    public function newAction(Request $request) {
        $subjectSource = new SubjectSource();
        $form = $this->createForm(SubjectSourceType::class, $subjectSource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($subjectSource);
            $em->flush();

            $this->addFlash('success', 'The new subjectSource was created.');
            return $this->redirectToRoute('subject_source_show', array('id' => $subjectSource->getId()));
        }

        return array(
            'subjectSource' => $subjectSource,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a SubjectSource entity.
     *
     * @Route("/{id}", name="subject_source_show")
     * @Method("GET")
     * @Template()
     * @param SubjectSource $subjectSource
     */
    public function showAction(SubjectSource $subjectSource) {

        return array(
            'subjectSource' => $subjectSource,
        );
    }

    /**
     * Displays a form to edit an existing SubjectSource entity.
     *
     * @Route("/{id}/edit", name="subject_source_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * 
     * @param Request $request
     * @param SubjectSource $subjectSource
     */
    public function editAction(Request $request, SubjectSource $subjectSource) {
        $editForm = $this->createForm(SubjectSourceType::class, $subjectSource);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The subjectSource has been updated.');
            return $this->redirectToRoute('subject_source_show', array('id' => $subjectSource->getId()));
        }

        return array(
            'subjectSource' => $subjectSource,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a SubjectSource entity.
     *
     * @Route("/{id}/delete", name="subject_source_delete")
     * @Method("GET")
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * 
     * @param Request $request
     * @param SubjectSource $subjectSource
     */
    public function deleteAction(Request $request, SubjectSource $subjectSource) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($subjectSource);
        $em->flush();
        $this->addFlash('success', 'The subjectSource was deleted.');

        return $this->redirectToRoute('subject_source_index');
    }

}
