<?php

namespace App\Controller;

use App\Entity\WorkCategory;
use App\Form\WorkCategoryType;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * WorkCategory controller.
 *
 * @Route("/work_category")
 */
class WorkCategoryController extends AbstractController  implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all WorkCategory entities.
     *
     * @Route("/", name="work_category_index", methods={"GET"})
     *
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:WorkCategory e ORDER BY e.id';
        $query = $em->createQuery($dql);
        ;
        $workCategories = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'workCategories' => $workCategories,
        );
    }

    /**
     * Creates a new WorkCategory entity.
     *
     * @Route("/new", name="work_category_new", methods={"GET","POST"})
     *
     * @Template()
     * @Security("has_role('ROLE_CONTENT_EDITOR')")
     *
     * @param Request $request
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
        $workCategory = new WorkCategory();
        $form = $this->createForm(WorkCategoryType::class, $workCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($workCategory);
            $em->flush();

            $this->addFlash('success', 'The new workCategory was created.');
            return $this->redirectToRoute('work_category_show', array('id' => $workCategory->getId()));
        }

        return array(
            'workCategory' => $workCategory,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a WorkCategory entity.
     *
     * @Route("/{id}", name="work_category_show", methods={"GET"})
     *
     * @Template()
     * @param WorkCategory $workCategory
     */
    public function showAction(WorkCategory $workCategory) {

        return array(
            'workCategory' => $workCategory,
        );
    }

    /**
     * Displays a form to edit an existing WorkCategory entity.
     *
     * @Route("/{id}/edit", name="work_category_edit", methods={"GET","POST"})
     *
     * @Template()
     * @Security("has_role('ROLE_CONTENT_EDITOR')")
     *
     * @param Request $request
     * @param WorkCategory $workCategory
     */
    public function editAction(Request $request, WorkCategory $workCategory, EntityManagerInterface $em) {
        $editForm = $this->createForm(WorkCategoryType::class, $workCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The workCategory has been updated.');
            return $this->redirectToRoute('work_category_show', array('id' => $workCategory->getId()));
        }

        return array(
            'workCategory' => $workCategory,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a WorkCategory entity.
     *
     * @Route("/{id}/delete", name="work_category_delete", methods={"GET"})
     *
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     *
     * @param Request $request
     * @param WorkCategory $workCategory
     */
    public function deleteAction(Request $request, WorkCategory $workCategory, EntityManagerInterface $em) {
        $em->remove($workCategory);
        $em->flush();
        $this->addFlash('success', 'The workCategory was deleted.');

        return $this->redirectToRoute('work_category_index');
    }

}
