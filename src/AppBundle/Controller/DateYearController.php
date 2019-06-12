<?php

namespace AppBundle\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * DateYear controller.
 *
 * @Route("/date")
 */
class DateYearController extends Controller {

    /**
     * Lists all DateYear entities.
     *
     * @Route("/", name="date_index", methods={"GET"})
     *
     * @Template()
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     *
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:DateYear e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $dateYears = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'dateYears' => $dateYears,
        );
    }

}
