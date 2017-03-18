<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Contribution;
use AppBundle\Form\ContributionType;

/**
 * Contribution controller.
 *
 * @Route("/contribution")
 */
class ContributionController extends Controller
{
    /**
     * Lists all Contribution entities.
     *
     * @Route("/", name="contribution_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Contribution e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $contributions = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'contributions' => $contributions,
        );
    }
    /**
     * Search for Contribution entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:Contribution repository. Replace the fieldName with
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
     * @Route("/search", name="contribution_search")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Contribution');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->searchQuery($q);
			$paginator = $this->get('knp_paginator');
			$contributions = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$contributions = array();
		}

        return array(
            'contributions' => $contributions,
			'q' => $q,
        );
    }
    /**
     * Full text search for Contribution entities.
	 *
	 * To make this work, add a method like this one to the 
	 * AppBundle:Contribution repository. Replace the fieldName with
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
	 * fulltext indexes on your Contribution entity.
	 *     ORM\Index(name="alias_name_idx",columns="name", flags={"fulltext"})
	 *
     *
     * @Route("/fulltext", name="contribution_fulltext")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function fulltextAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Contribution');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$contributions = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$contributions = array();
		}

        return array(
            'contributions' => $contributions,
			'q' => $q,
        );
    }

    /**
     * Creates a new Contribution entity.
     *
     * @Route("/new", name="contribution_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        $contribution = new Contribution();
        $form = $this->createForm('AppBundle\Form\ContributionType', $contribution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contribution);
            $em->flush();

            $this->addFlash('success', 'The new contribution was created.');
            return $this->redirectToRoute('contribution_show', array('id' => $contribution->getId()));
        }

        return array(
            'contribution' => $contribution,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Contribution entity.
     *
     * @Route("/{id}", name="contribution_show")
     * @Method("GET")
     * @Template()
	 * @param Contribution $contribution
     */
    public function showAction(Contribution $contribution)
    {

        return array(
            'contribution' => $contribution,
        );
    }

    /**
     * Displays a form to edit an existing Contribution entity.
     *
     * @Route("/{id}/edit", name="contribution_edit")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
	 * @param Contribution $contribution
     */
    public function editAction(Request $request, Contribution $contribution)
    {
        $editForm = $this->createForm('AppBundle\Form\ContributionType', $contribution);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The contribution has been updated.');
            return $this->redirectToRoute('contribution_show', array('id' => $contribution->getId()));
        }

        return array(
            'contribution' => $contribution,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Contribution entity.
     *
     * @Route("/{id}/delete", name="contribution_delete")
     * @Method("GET")
	 * @param Request $request
	 * @param Contribution $contribution
     */
    public function deleteAction(Request $request, Contribution $contribution)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($contribution);
        $em->flush();
        $this->addFlash('success', 'The contribution was deleted.');

        return $this->redirectToRoute('contribution_index');
    }
}
