<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DateCategory;
use AppBundle\Form\DateCategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * DateCategory controller.
 *
 * @Route("/date_category")
 */
class DateCategoryController extends Controller {

    /**
     * Lists all DateCategory entities.
     *
     * @Route("/", name="date_category_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:DateCategory e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $dateCategories = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'dateCategories' => $dateCategories,
        );
    }

    /**
     * Creates a new DateCategory entity.
     *
     * @Route("/new", name="date_category_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * 
     * @param Request $request
     */
    public function newAction(Request $request) {
        $dateCategory = new DateCategory();
        $form = $this->createForm(DateCategoryType::class, $dateCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($dateCategory);
            $em->flush();

            $this->addFlash('success', 'The new dateCategory was created.');
            return $this->redirectToRoute('date_category_show', array('id' => $dateCategory->getId()));
        }

        return array(
            'dateCategory' => $dateCategory,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a DateCategory entity.
     *
     * @Route("/{id}", name="date_category_show")
     * @Method("GET")
     * @Template()
     * @param DateCategory $dateCategory
     */
    public function showAction(DateCategory $dateCategory) {

        return array(
            'dateCategory' => $dateCategory,
        );
    }

    /**
     * Displays a form to edit an existing DateCategory entity.
     *
     * @Route("/{id}/edit", name="date_category_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * 
     * @param Request $request
     * @param DateCategory $dateCategory
     */
    public function editAction(Request $request, DateCategory $dateCategory) {
        $editForm = $this->createForm(DateCategoryType::class, $dateCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The dateCategory has been updated.');
            return $this->redirectToRoute('date_category_show', array('id' => $dateCategory->getId()));
        }

        return array(
            'dateCategory' => $dateCategory,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a DateCategory entity.
     *
     * @Route("/{id}/delete", name="date_category_delete")
     * @Method("GET")
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     * 
     * @param Request $request
     * @param DateCategory $dateCategory
     */
    public function deleteAction(Request $request, DateCategory $dateCategory) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($dateCategory);
        $em->flush();
        $this->addFlash('success', 'The dateCategory was deleted.');

        return $this->redirectToRoute('date_category_index');
    }

}
