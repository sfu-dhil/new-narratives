<?php

namespace AppBundle\Controller;

use AppBundle\Entity\WorkCategory;
use AppBundle\Form\WorkCategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * WorkCategory controller.
 *
 * @Route("/work_category")
 */
class WorkCategoryController extends Controller {

    /**
     * Lists all WorkCategory entities.
     *
     * @Route("/", name="work_category_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:WorkCategory e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $workCategories = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'workCategories' => $workCategories,
        );
    }

    /**
     * Creates a new WorkCategory entity.
     *
     * @Route("/new", name="work_category_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        if (!$this->isGranted('ROLE_BLOG_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $workCategory = new WorkCategory();
        $form = $this->createForm(WorkCategoryType::class, $workCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($workCategory);
            $em->flush();

            $this->addFlash('success', 'The new workCategory was created.');
            return $this->redirectToRoute('work_category_show', array('id' => $workCategory->getId()));
        }

        return array(
            'workCategory' => $workCategory,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a WorkCategory entity.
     *
     * @Route("/{id}", name="work_category_show")
     * @Method("GET")
     * @Template()
     * @param WorkCategory $workCategory
     */
    public function showAction(WorkCategory $workCategory) {

        return array(
            'workCategory' => $workCategory,
        );
    }

    /**
     * Displays a form to edit an existing WorkCategory entity.
     *
     * @Route("/{id}/edit", name="work_category_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param WorkCategory $workCategory
     */
    public function editAction(Request $request, WorkCategory $workCategory) {
        if (!$this->isGranted('ROLE_BLOG_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $editForm = $this->createForm(WorkCategoryType::class, $workCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The workCategory has been updated.');
            return $this->redirectToRoute('work_category_show', array('id' => $workCategory->getId()));
        }

        return array(
            'workCategory' => $workCategory,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a WorkCategory entity.
     *
     * @Route("/{id}/delete", name="work_category_delete")
     * @Method("GET")
     * @param Request $request
     * @param WorkCategory $workCategory
     */
    public function deleteAction(Request $request, WorkCategory $workCategory) {
        if (!$this->isGranted('ROLE_BLOG_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($workCategory);
        $em->flush();
        $this->addFlash('success', 'The workCategory was deleted.');

        return $this->redirectToRoute('work_category_index');
    }

}
