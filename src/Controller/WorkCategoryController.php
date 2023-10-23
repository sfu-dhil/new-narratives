<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\WorkCategory;
use App\Form\WorkCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/work_category')]
class WorkCategoryController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    #[Route(path: '/', name: 'work_category_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, EntityManagerInterface $em) : array {
        $dql = 'SELECT e FROM App:WorkCategory e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $workCategories = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'workCategories' => $workCategories,
        ];
    }

    #[Route(path: '/new', name: 'work_category_new', methods: ['GET', 'POST'])]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_EDITOR')")]
    public function new(Request $request, EntityManagerInterface $em) : array|RedirectResponse {
        $workCategory = new WorkCategory();
        $form = $this->createForm(WorkCategoryType::class, $workCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($workCategory);
            $em->flush();

            $this->addFlash('success', 'The new workCategory was created.');

            return $this->redirectToRoute('work_category_show', ['id' => $workCategory->getId()]);
        }

        return [
            'workCategory' => $workCategory,
            'form' => $form->createView(),
        ];
    }

    #[Route(path: '/{id}', name: 'work_category_show', methods: ['GET'])]
    #[Template]
    public function show(WorkCategory $workCategory) : array {
        return [
            'workCategory' => $workCategory,
        ];
    }

    #[Route(path: '/{id}/edit', name: 'work_category_edit', methods: ['GET', 'POST'])]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_EDITOR')")]
    public function edit(Request $request, WorkCategory $workCategory, EntityManagerInterface $em) : array|RedirectResponse {
        $editForm = $this->createForm(WorkCategoryType::class, $workCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The workCategory has been updated.');

            return $this->redirectToRoute('work_category_show', ['id' => $workCategory->getId()]);
        }

        return [
            'workCategory' => $workCategory,
            'edit_form' => $editForm->createView(),
        ];
    }

    #[Route(path: '/{id}/delete', name: 'work_category_delete', methods: ['GET'])]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    public function delete(WorkCategory $workCategory, EntityManagerInterface $em) : RedirectResponse {
        $em->remove($workCategory);
        $em->flush();
        $this->addFlash('success', 'The workCategory was deleted.');

        return $this->redirectToRoute('work_category_index');
    }
}
