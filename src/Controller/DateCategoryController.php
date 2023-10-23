<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\DateCategory;
use App\Form\DateCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/date_category')]
class DateCategoryController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    #[Route(path: '/', name: 'date_category_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, EntityManagerInterface $em) : array {
        $dql = 'SELECT e FROM App:DateCategory e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $dateCategories = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'dateCategories' => $dateCategories,
        ];
    }

    #[Route(path: '/new', name: 'date_category_new', methods: ['GET', 'POST'])]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_EDITOR')")]
    public function new(Request $request, EntityManagerInterface $em) : array|RedirectResponse {
        $dateCategory = new DateCategory();
        $form = $this->createForm(DateCategoryType::class, $dateCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($dateCategory);
            $em->flush();

            $this->addFlash('success', 'The new dateCategory was created.');

            return $this->redirectToRoute('date_category_show', ['id' => $dateCategory->getId()]);
        }

        return [
            'dateCategory' => $dateCategory,
            'form' => $form->createView(),
        ];
    }

    #[Route(path: '/{id}', name: 'date_category_show', methods: ['GET'])]
    #[Template]
    public function show(DateCategory $dateCategory) : array {
        return [
            'dateCategory' => $dateCategory,
        ];
    }

    #[Route(path: '/{id}/edit', name: 'date_category_edit', methods: ['GET', 'POST'])]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_EDITOR')")]
    public function edit(Request $request, DateCategory $dateCategory, EntityManagerInterface $em) : array|RedirectResponse {
        $editForm = $this->createForm(DateCategoryType::class, $dateCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The dateCategory has been updated.');

            return $this->redirectToRoute('date_category_show', ['id' => $dateCategory->getId()]);
        }

        return [
            'dateCategory' => $dateCategory,
            'edit_form' => $editForm->createView(),
        ];
    }

    #[Route(path: '/{id}/delete', name: 'date_category_delete', methods: ['GET'])]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    public function delete(DateCategory $dateCategory, EntityManagerInterface $em) : RedirectResponse {
        $em->remove($dateCategory);
        $em->flush();
        $this->addFlash('success', 'The dateCategory was deleted.');

        return $this->redirectToRoute('date_category_index');
    }
}
