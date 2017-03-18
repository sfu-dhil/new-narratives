<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\DateCategory;
use AppBundle\Form\DateCategoryType;

/**
 * DateCategory controller.
 *
 * @Route("/date_category")
 */
class DateCategoryController extends Controller
{
    /**
     * Lists all DateCategory entities.
     *
     * @Route("/", name="date_category_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
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
     * Search for DateCategory entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:DateCategory repository. Replace the fieldName with
	 * something appropriate, and adjust the generated search.html.twig
	 * template.
	 * 
     //    public function searchQuery($q) {
     //        $qb = $this->createQueryBuilder('e');
     //        $qb->where("e.fieldName like '%$q%'");
     //        return $qb->getQuery();
     //    }
	 *
     *
     * @Route("/search", name="date_category_search")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:DateCategory');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->searchQuery($q);
			$paginator = $this->get('knp_paginator');
			$dateCategories = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$dateCategories = array();
		}

        return array(
            'dateCategories' => $dateCategories,
			'q' => $q,
        );
    }
    /**
     * Full text search for DateCategory entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:DateCategory repository. Replace the fieldName with
	 * something appropriate, and adjust the generated fulltext.html.twig
	 * template.
	 * 
	//    public function fulltextQuery($q) {
	//        $qb = $this->createQueryBuilder('e');
	//        $qb->addSelect("MATCH_AGAINST (e.name, :q 'IN BOOLEAN MODE') as score");
	//        $qb->add('where', "MATCH_AGAINST (e.name, :q 'IN BOOLEAN MODE') > 0.5");
	//        $qb->orderBy('score', 'desc');
	//        $qb->setParameter('q', $q);
	//        return $qb->getQuery();
	//    }	 
	 * 
	 * Requires a MatchAgainst function be added to doctrine, and appropriate
	 * fulltext indexes on your DateCategory entity.
	 *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
	 *
     *
     * @Route("/fulltext", name="date_category_fulltext")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function fulltextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:DateCategory');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$dateCategories = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$dateCategories = array();
		}

        return array(
            'dateCategories' => $dateCategories,
			'q' => $q,
        );
    }

    /**
     * Creates a new DateCategory entity.
     *
     * @Route("/new", name="date_category_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        $dateCategory = new DateCategory();
        $form = $this->createForm('AppBundle\Form\DateCategoryType', $dateCategory);
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
    public function showAction(DateCategory $dateCategory)
    {

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
	 * @param Request $request
	 * @param DateCategory $dateCategory
     */
    public function editAction(Request $request, DateCategory $dateCategory)
    {
        $editForm = $this->createForm('AppBundle\Form\DateCategoryType', $dateCategory);
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
	 * @param Request $request
	 * @param DateCategory $dateCategory
     */
    public function deleteAction(Request $request, DateCategory $dateCategory)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($dateCategory);
        $em->flush();
        $this->addFlash('success', 'The dateCategory was deleted.');

        return $this->redirectToRoute('date_category_index');
    }
}
