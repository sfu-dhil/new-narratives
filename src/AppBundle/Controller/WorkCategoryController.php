<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\WorkCategory;
use AppBundle\Form\WorkCategoryType;

/**
 * WorkCategory controller.
 *
 * @Route("/work_category")
 */
class WorkCategoryController extends Controller
{
    /**
     * Lists all WorkCategory entities.
     *
     * @Route("/", name="work_category_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
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
     * Search for WorkCategory entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:WorkCategory repository. Replace the fieldName with
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
     * @Route("/search", name="work_category_search")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:WorkCategory');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->searchQuery($q);
			$paginator = $this->get('knp_paginator');
			$workCategories = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$workCategories = array();
		}

        return array(
            'workCategories' => $workCategories,
			'q' => $q,
        );
    }
    /**
     * Full text search for WorkCategory entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:WorkCategory repository. Replace the fieldName with
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
	 * fulltext indexes on your WorkCategory entity.
	 *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
	 *
     *
     * @Route("/fulltext", name="work_category_fulltext")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function fulltextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:WorkCategory');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$workCategories = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$workCategories = array();
		}

        return array(
            'workCategories' => $workCategories,
			'q' => $q,
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
    public function newAction(Request $request)
    {
        $workCategory = new WorkCategory();
        $form = $this->createForm('AppBundle\Form\WorkCategoryType', $workCategory);
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
    public function showAction(WorkCategory $workCategory)
    {

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
    public function editAction(Request $request, WorkCategory $workCategory)
    {
        $editForm = $this->createForm('AppBundle\Form\WorkCategoryType', $workCategory);
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
    public function deleteAction(Request $request, WorkCategory $workCategory)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($workCategory);
        $em->flush();
        $this->addFlash('success', 'The workCategory was deleted.');

        return $this->redirectToRoute('work_category_index');
    }
}
