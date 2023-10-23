<?php

declare(strict_types=1);

namespace App\Controller;

use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\BlogBundle\Repository\PageRepository;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    #[Route(path: '/', name: 'homepage')]
    #[Template]
    public function index(PageRepository $repo) : array {
        return [
            'homepage' => $repo->findHomepage(),
        ];
    }

    #[Route(path: '/privacy', name: 'privacy')]
    #[Template]
    public function privacy() : void {}
}
