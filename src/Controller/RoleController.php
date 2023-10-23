<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Role;
use App\Form\RoleType;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Role controller.
 */
#[Route(path: '/role')]
class RoleController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    #[Route(path: '/', name: 'role_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, EntityManagerInterface $em) : array {
        $dql = 'SELECT e FROM App:Role e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $roles = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'roles' => $roles,
        ];
    }

    #[Route(path: '/new', name: 'role_new', methods: ['GET', 'POST'])]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_EDITOR')")]
    public function new(Request $request, EntityManagerInterface $em) : array|RedirectResponse {
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

    #[Route(path: '/{id}', name: 'role_show', methods: ['GET'])]
    #[Template]
    public function show(Role $role, PersonRepository $repo) : array {
        $counts = $repo->countByRole($role)->execute();

        return [
            'role' => $role,
            'counts' => $counts,
        ];
    }

    #[Route(path: '/{id}/edit', name: 'role_edit', methods: ['GET', 'POST'])]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_EDITOR')")]
    public function edit(Request $request, Role $role, EntityManagerInterface $em) : array|RedirectResponse {
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
     */
    #[Route(path: '/{id}/delete', name: 'role_delete', methods: ['GET'])]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    public function delete(Role $role, EntityManagerInterface $em) : RedirectResponse {
        $em->remove($role);
        $em->flush();
        $this->addFlash('success', 'The role was deleted.');

        return $this->redirectToRoute('role_index');
    }
}
