<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Role;
use App\Form\RoleType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Role controller.
 *
 * @Route("/role")
 */
class RoleController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all Role entities.
     *
     * @Route("/", name="role_index", methods={"GET"})
     *
     * @Template()
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:Role e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $roles = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return [
            'roles' => $roles,
        ];
    }

    /**
     * Creates a new Role entity.
     *
     * @Route("/new", name="role_new", methods={"GET","POST"})
     *
     * @Template()
     * @Security("has_role('ROLE_CONTENT_EDITOR')")
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($role);
            $em->flush();

            $this->addFlash('success', 'The new role was created.');

            return $this->redirectToRoute('role_show', ['id' => $role->getId()]);
        }

        return [
            'role' => $role,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Role entity.
     *
     * @Route("/{id}", name="role_show", methods={"GET"})
     *
     * @Template()
     */
    public function showAction(Role $role) {
        return [
            'role' => $role,
        ];
    }

    /**
     * Displays a form to edit an existing Role entity.
     *
     * @Route("/{id}/edit", name="role_edit", methods={"GET","POST"})
     *
     * @Template()
     * @Security("has_role('ROLE_CONTENT_EDITOR')")
     */
    public function editAction(Request $request, Role $role, EntityManagerInterface $em) {
        $editForm = $this->createForm(RoleType::class, $role);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The role has been updated.');

            return $this->redirectToRoute('role_show', ['id' => $role->getId()]);
        }

        return [
            'role' => $role,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Role entity.
     *
     * @Route("/{id}/delete", name="role_delete", methods={"GET"})
     *
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     */
    public function deleteAction(Request $request, Role $role, EntityManagerInterface $em) {
        $em->remove($role);
        $em->flush();
        $this->addFlash('success', 'The role was deleted.');

        return $this->redirectToRoute('role_index');
    }
}
