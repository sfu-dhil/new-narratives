<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Work;
use AppBundle\Form\WorkContributionsType;
use AppBundle\Form\WorkDatesType;
use AppBundle\Form\WorkSearchType;
use AppBundle\Form\WorkType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Work controller.
 *
 * @Route("/work")
 */
class WorkController extends Controller {

    /**
     * Lists all Work entities.
     *
     * @Route("/", name="work_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Work e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $works = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'works' => $works,
        );
    }

    /**
     * Full text search for Work entities.
     *
     * @Route("/search", name="work_search")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Work');
        $works = array();
        $form = $this->createForm(WorkSearchType::class, null, array());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $query = $repo->searchQuery($form->getData());
            $paginator = $this->get('knp_paginator');
            $works = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
        }

        return array(
            'works' => $works,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Work entity.
     *
     * @Route("/new", name="work_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @Security("has_role('ROLE_CONTENT_EDITOR')")
     *
     * @param Request $request
     */
    public function newAction(Request $request) {
        $work = new Work();
        $form = $this->createForm(WorkType::class, $work);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($work);
            $em->flush();

            $this->addFlash('success', 'The new work was created.');
            return $this->redirectToRoute('work_show', array('id' => $work->getId()));
        }

        return array(
            'work' => $work,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Work entity.
     *
     * @Route("/{id}", name="work_show")
     * @Method("GET")
     * @Template()
     * @param Work $work
     */
    public function showAction(Work $work) {

        return array(
            'work' => $work,
        );
    }

    /**
     * Displays a form to edit an existing Work entity.
     *
     * @Route("/{id}/edit", name="work_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @Security("has_role('ROLE_CONTENT_EDITOR')")
     *
     * @param Request $request
     * @param Work $work
     */
    public function editAction(Request $request, Work $work) {
        $editForm = $this->createForm(WorkType::class, $work);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The work has been updated.');
            return $this->redirectToRoute('work_show', array('id' => $work->getId()));
        }

        return array(
            'work' => $work,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Work entity.
     *
     * @Route("/{id}/delete", name="work_delete")
     * @Method("GET")
     * @Security("has_role('ROLE_CONTENT_EDITOR')")
     *
     * @param Request $request
     * @param Work $work
     */
    public function deleteAction(Request $request, Work $work) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($work);
        $em->flush();
        $this->addFlash('success', 'The work was deleted.');

        return $this->redirectToRoute('work_index');
    }

    /**
     * Add/remove dates to a work.
     *
     * @Route("/{id}/dates", name="work_dates")
     * @Method({"GET", "POST"})
     * @Template()
     * @Security("has_role('ROLE_CONTENT_EDITOR')")
     *
     * @param Request $request
     * @param Work $work
     */
    public function workDatesAction(Request $request, Work $work) {
        $form = $this->createForm(WorkDatesType::class, $work, array(
            'work' => $work
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The dates have been updated.');
            return $this->redirectToRoute('work_show', array('id' => $work->getId()));
        }
        return array(
            'work' => $work,
            'form' => $form->createView(),
        );
    }

    /**
     * Add contributions to a work.
     *
     * @Route("/{id}/contributions", name="work_contributions")
     * @Method({"GET", "POST"})
     * @Template()
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     *
     * @param Request $request
     * @param Work $work
     */
    public function workContributionsAction(Request $request, Work $work) {
        $form = $this->createForm(WorkContributionsType::class, $work, array(
            'work' => $work
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The contributions have been updated.');
            return $this->redirectToRoute('work_show', array('id' => $work->getId()));
        }
        return array(
            'work' => $work,
            'form' => $form->createView(),
        );
    }

}
