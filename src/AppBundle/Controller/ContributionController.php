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
}
