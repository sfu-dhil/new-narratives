<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Subject;
use AppBundle\Form\SubjectType;

/**
 * Subject controller.
 *
 * @Route("/subject")
 */
class SubjectController extends Controller
{
    /**
     * Lists all Subject entities.
     *
     * @Route("/", name="subject_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Subject e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $subjects = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'subjects' => $subjects,
        );
    }
    /**
     * Search for Subject entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:Subject repository. Replace the fieldName with
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
     * @Route("/search", name="subject_search")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Subject');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->searchQuery($q);
			$paginator = $this->get('knp_paginator');
			$subjects = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$subjects = array();
		}

        return array(
            'subjects' => $subjects,
			'q' => $q,
        );
    }
    /**
     * Full text search for Subject entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:Subject repository. Replace the fieldName with
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
	 * fulltext indexes on your Subject entity.
	 *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
	 *
     *
     * @Route("/fulltext", name="subject_fulltext")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function fulltextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Subject');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$subjects = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$subjects = array();
		}

        return array(
            'subjects' => $subjects,
			'q' => $q,
        );
    }

    /**
     * Creates a new Subject entity.
     *
     * @Route("/new", name="subject_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        $subject = new Subject();
        $form = $this->createForm('AppBundle\Form\SubjectType', $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($subject);
            $em->flush();

            $this->addFlash('success', 'The new subject was created.');
            return $this->redirectToRoute('subject_show', array('id' => $subject->getId()));
        }

        return array(
            'subject' => $subject,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Subject entity.
     *
     * @Route("/{id}", name="subject_show")
     * @Method("GET")
     * @Template()
	 * @param Subject $subject
     */
    public function showAction(Subject $subject)
    {

        return array(
            'subject' => $subject,
        );
    }

    /**
     * Displays a form to edit an existing Subject entity.
     *
     * @Route("/{id}/edit", name="subject_edit")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
	 * @param Subject $subject
     */
    public function editAction(Request $request, Subject $subject)
    {
        $editForm = $this->createForm('AppBundle\Form\SubjectType', $subject);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The subject has been updated.');
            return $this->redirectToRoute('subject_show', array('id' => $subject->getId()));
        }

        return array(
            'subject' => $subject,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Subject entity.
     *
     * @Route("/{id}/delete", name="subject_delete")
     * @Method("GET")
	 * @param Request $request
	 * @param Subject $subject
     */
    public function deleteAction(Request $request, Subject $subject)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($subject);
        $em->flush();
        $this->addFlash('success', 'The subject was deleted.');

        return $this->redirectToRoute('subject_index');
    }
}
