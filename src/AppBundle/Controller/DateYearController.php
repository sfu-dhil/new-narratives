<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\DateYear;
use AppBundle\Form\DateYearType;

/**
 * DateYear controller.
 *
 * @Route("/date")
 */
class DateYearController extends Controller
{
    /**
     * Lists all DateYear entities.
     *
     * @Route("/", name="date_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:DateYear e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $dateYears = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'dateYears' => $dateYears,
        );
    }
    /**
     * Search for DateYear entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:DateYear repository. Replace the fieldName with
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
     * @Route("/search", name="date_search")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:DateYear');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->searchQuery($q);
			$paginator = $this->get('knp_paginator');
			$dateYears = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$dateYears = array();
		}

        return array(
            'dateYears' => $dateYears,
			'q' => $q,
        );
    }
    /**
     * Full text search for DateYear entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:DateYear repository. Replace the fieldName with
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
	 * fulltext indexes on your DateYear entity.
	 *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
	 *
     *
     * @Route("/fulltext", name="date_fulltext")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function fulltextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:DateYear');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$dateYears = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$dateYears = array();
		}

        return array(
            'dateYears' => $dateYears,
			'q' => $q,
        );
    }

    /**
     * Creates a new DateYear entity.
     *
     * @Route("/new", name="date_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        $dateYear = new DateYear();
        $form = $this->createForm('AppBundle\Form\DateYearType', $dateYear);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($dateYear);
            $em->flush();

            $this->addFlash('success', 'The new dateYear was created.');
            return $this->redirectToRoute('date_show', array('id' => $dateYear->getId()));
        }

        return array(
            'dateYear' => $dateYear,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a DateYear entity.
     *
     * @Route("/{id}", name="date_show")
     * @Method("GET")
     * @Template()
	 * @param DateYear $dateYear
     */
    public function showAction(DateYear $dateYear)
    {

        return array(
            'dateYear' => $dateYear,
        );
    }

    /**
     * Displays a form to edit an existing DateYear entity.
     *
     * @Route("/{id}/edit", name="date_edit")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
	 * @param DateYear $dateYear
     */
    public function editAction(Request $request, DateYear $dateYear)
    {
        $editForm = $this->createForm('AppBundle\Form\DateYearType', $dateYear);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The dateYear has been updated.');
            return $this->redirectToRoute('date_show', array('id' => $dateYear->getId()));
        }

        return array(
            'dateYear' => $dateYear,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a DateYear entity.
     *
     * @Route("/{id}/delete", name="date_delete")
     * @Method("GET")
	 * @param Request $request
	 * @param DateYear $dateYear
     */
    public function deleteAction(Request $request, DateYear $dateYear)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($dateYear);
        $em->flush();
        $this->addFlash('success', 'The dateYear was deleted.');

        return $this->redirectToRoute('date_index');
    }
}
