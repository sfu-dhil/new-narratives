<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contribution controller.
 *
 * @Route("/contribution")
 */
class ContributionController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Contribution entities.
     *
     * @Route("/", name="contribution_index", methods={"GET"})
     * @Template()
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     *
     * @return array
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:Contribution e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $contributions = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'contributions' => $contributions,
        ];
    }
}
