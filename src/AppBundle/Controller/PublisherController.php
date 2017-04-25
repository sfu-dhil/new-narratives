<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Publisher;
use AppBundle\Form\PublisherType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Publisher controller.
 *
 * @Route("/publisher")
 */
class PublisherController extends Controller
{
    /**
     * Lists all Publisher entities.
     *
     * @Route("/", name="publisher_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Publisher e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $publishers = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'publishers' => $publishers,
        );
    }

    /**
     * Full text search for Publisher entities.
     *
     * @Route("/search", name="publisher_search")
     * @Method("GET")
     * @Template()
	 * @param Request $request
	 * @return array
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:Publisher');
		$q = $request->query->get('q');
		if($q) {
	        $query = $repo->fulltextQuery($q);
			$paginator = $this->get('knp_paginator');
			$publishers = $paginator->paginate($query, $request->query->getInt('page', 1), 25);
		} else {
			$publishers = array();
		}

        return array(
            'publishers' => $publishers,
			'q' => $q,
        );
    }

    /**
     * Creates a new Publisher entity.
     *
     * @Route("/new", name="publisher_new")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
     */
    public function newAction(Request $request)
    {
        if( ! $this->isGranted('ROLE_BLOG_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $publisher = new Publisher();
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($publisher);
            $em->flush();

            $this->addFlash('success', 'The new publisher was created.');
            return $this->redirectToRoute('publisher_show', array('id' => $publisher->getId()));
        }

        return array(
            'publisher' => $publisher,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Publisher entity.
     *
     * @Route("/{id}", name="publisher_show")
     * @Method("GET")
     * @Template()
	 * @param Publisher $publisher
     */
    public function showAction(Publisher $publisher)
    {

        return array(
            'publisher' => $publisher,
        );
    }

    /**
     * Displays a form to edit an existing Publisher entity.
     *
     * @Route("/{id}/edit", name="publisher_edit")
     * @Method({"GET", "POST"})
     * @Template()
	 * @param Request $request
	 * @param Publisher $publisher
     */
    public function editAction(Request $request, Publisher $publisher)
    {
        if( ! $this->isGranted('ROLE_BLOG_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $editForm = $this->createForm(PublisherType::class, $publisher);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The publisher has been updated.');
            return $this->redirectToRoute('publisher_show', array('id' => $publisher->getId()));
        }

        return array(
            'publisher' => $publisher,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Publisher entity.
     *
     * @Route("/{id}/delete", name="publisher_delete")
     * @Method("GET")
	 * @param Request $request
	 * @param Publisher $publisher
     */
    public function deleteAction(Request $request, Publisher $publisher)
    {
        if( ! $this->isGranted('ROLE_BLOG_ADMIN')) {
            $this->addFlash('danger', 'You must login to access this page.');
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($publisher);
        $em->flush();
        $this->addFlash('success', 'The publisher was deleted.');

        return $this->redirectToRoute('publisher_index');
    }
}
