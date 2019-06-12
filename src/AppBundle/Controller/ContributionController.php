<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contribution controller.
 *
 * @Route("/contribution")
 */
class ContributionController extends Controller {

    /**
     * Lists all Contribution entities.
     *
     * @param Request $request
     *
     * @Route("/", name="contribution_index", methods={"GET"})
     * @Template()
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     *
     * @return array
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:Contribution e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $contributions = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'contributions' => $contributions,
        ];
    }

}
