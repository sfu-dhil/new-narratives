<?php

declare(strict_types=1);

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/contribution')]
class ContributionController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    #[Route(path: '/', name: 'contribution_index', methods: ['GET'])]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    public function index(Request $request, EntityManagerInterface $em) : array {
        $dql = 'SELECT e FROM App:Contribution e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $contributions = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'contributions' => $contributions,
        ];
    }
}
