<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\SubjectSource;
use App\Form\SubjectSourceType;
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
 * SubjectSource controller.
 */
#[Route(path: '/subject_source')]
class SubjectSourceController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    #[Route(path: '/', name: 'subject_source_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, EntityManagerInterface $em) : array {
        $dql = 'SELECT e FROM App:SubjectSource e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $subjectSources = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'subjectSources' => $subjectSources,
        ];
    }

    #[Route(path: '/new', name: 'subject_source_new', methods: ['GET', 'POST'])]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_EDITOR')")]
    public function new(Request $request, EntityManagerInterface $em) : array|RedirectResponse {
        $subjectSource = new SubjectSource();
        $form = $this->createForm(SubjectSourceType::class, $subjectSource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($subjectSource);
            $em->flush();

            $this->addFlash('success', 'The new subjectSource was created.');

            return $this->redirectToRoute('subject_source_show', ['id' => $subjectSource->getId()]);
        }

        return [
            'subjectSource' => $subjectSource,
            'form' => $form->createView(),
        ];
    }

    #[Route(path: '/{id}', name: 'subject_source_show', methods: ['GET'])]
    #[Template]
    public function show(SubjectSource $subjectSource) : array {
        return [
            'subjectSource' => $subjectSource,
        ];
    }

    #[Route(path: '/{id}/edit', name: 'subject_source_edit', methods: ['GET', 'POST'])]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_EDITOR')")]
    public function edit(Request $request, SubjectSource $subjectSource, EntityManagerInterface $em) : array|RedirectResponse {
        $editForm = $this->createForm(SubjectSourceType::class, $subjectSource);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The subjectSource has been updated.');

            return $this->redirectToRoute('subject_source_show', ['id' => $subjectSource->getId()]);
        }

        return [
            'subjectSource' => $subjectSource,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a SubjectSource entity.
     */
    #[Route(path: '/{id}/delete', name: 'subject_source_delete', methods: ['GET'])]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    public function delete(SubjectSource $subjectSource, EntityManagerInterface $em) : RedirectResponse {
        $em->remove($subjectSource);
        $em->flush();
        $this->addFlash('success', 'The subjectSource was deleted.');

        return $this->redirectToRoute('subject_source_index');
    }
}
