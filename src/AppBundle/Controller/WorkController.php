<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Work;
use AppBundle\Form\WorkType;

/**
 * Work controller.
 *
 * @Route("/work")
 */
class WorkController extends Controller
{
    /**
     * Lists all Work entities.
     *
     * @Route("/", name="work_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
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
     * Search for Work entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:Work repository. Replace the fieldName with
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
     * @Route("/search", name="work_search")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Work');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->searchQuery($q);
			$paginator = $this->get('knp_paginator');
			$works = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$works = array();
		}

        return array(
            'works' => $works,
			'q' => $q,
        );
    }
    /**
     * Full text search for Work entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:Work repository. Replace the fieldName with
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
	 * fulltext indexes on your Work entity.
	 *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
	 *
     *
     * @Route("/fulltext", name="work_fulltext")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function fulltextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Work');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$works = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$works = array();
		}

        return array(
            'works' => $works,
			'q' => $q,
        );
    }

    /**
     * Creates a new Work entity.
     *
     * @Route("/new", name="work_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        $work = new Work();
        $form = $this->createForm('AppBundle\Form\WorkType', $work);
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
    public function showAction(Work $work)
    {

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
	 * @param Request $request
	 * @param Work $work
     */
    public function editAction(Request $request, Work $work)
    {
        $editForm = $this->createForm('AppBundle\Form\WorkType', $work);
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
	 * @param Request $request
	 * @param Work $work
     */
    public function deleteAction(Request $request, Work $work)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($work);
        $em->flush();
        $this->addFlash('success', 'The work was deleted.');

        return $this->redirectToRoute('work_index');
    }
}
