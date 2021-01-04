<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\DateCategory;
use App\Form\DateCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * DateCategory controller.
 *
 * @Route("/date_category")
 */
class DateCategoryController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all DateCategory entities.
     *
     * @Route("/", name="date_category_index", methods={"GET"})
     *
     * @Template
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:DateCategory e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $dateCategories = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'dateCategories' => $dateCategories,
        ];
    }

    /**
     * Creates a new DateCategory entity.
     *
     * @Route("/new", name="date_category_new", methods={"GET", "POST"})
     *
     * @Template
     * @Security("is_granted('ROLE_CONTENT_EDITOR')")
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
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

    /**
     * Finds and displays a DateCategory entity.
     *
     * @Route("/{id}", name="date_category_show", methods={"GET"})
     *
     * @Template
     */
    public function showAction(DateCategory $dateCategory) {
        return [
            'dateCategory' => $dateCategory,
        ];
    }

    /**
     * Displays a form to edit an existing DateCategory entity.
     *
     * @Route("/{id}/edit", name="date_category_edit", methods={"GET", "POST"})
     *
     * @Template
     * @Security("is_granted('ROLE_CONTENT_EDITOR')")
     */
    public function editAction(Request $request, DateCategory $dateCategory, EntityManagerInterface $em) {
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

    /**
     * Deletes a DateCategory entity.
     *
     * @Route("/{id}/delete", name="date_category_delete", methods={"GET"})
     *
     * @Security("is_granted('ROLE_CONTENT_ADMIN')")
     */
    public function deleteAction(Request $request, DateCategory $dateCategory, EntityManagerInterface $em) {
        $em->remove($dateCategory);
        $em->flush();
        $this->addFlash('success', 'The dateCategory was deleted.');

        return $this->redirectToRoute('date_category_index');
    }
}
