<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Subject;
use App\Form\SubjectSearchType;
use App\Form\SubjectType;
use App\Repository\SubjectRepository;
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
 * Subject controller.
 */
#[Route(path: '/subject')]
class SubjectController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    #[Route(path: '/', name: 'subject_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, EntityManagerInterface $em) : array {
        $dql = 'SELECT e FROM App:Subject e ORDER BY e.label';
        $query = $em->createQuery($dql);

        $subjects = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'subjects' => $subjects,
        ];
    }

    #[Route(path: '/search', name: 'subject_search', methods: ['GET'])]
    #[Template]
    public function search(Request $request, SubjectRepository $repo) : array {
        $form = $this->createForm(SubjectSearchType::class);
        $subjects = [];
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $query = $repo->searchQuery($form->getData());
            $subjects = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        }

        return [
            'search_form' => $form->createView(),
            'subjects' => $subjects,
        ];
    }

    #[Route(path: '/new', name: 'subject_new', methods: ['GET', 'POST'])]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_EDITOR')")]
    public function new(Request $request, EntityManagerInterface $em) : array|RedirectResponse {
        $subject = new Subject();
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($subject);
            $em->flush();

            $this->addFlash('success', 'The new subject was created.');

            return $this->redirectToRoute('subject_show', ['id' => $subject->getId()]);
        }

        return [
            'subject' => $subject,
            'form' => $form->createView(),
        ];
    }

    #[Route(path: '/{id}', name: 'subject_show', methods: ['GET'])]
    #[Template]
    public function show(Subject $subject) : ?array {
        return [
            'subject' => $subject,
        ];
    }

    #[Route(path: '/{id}/edit', name: 'subject_edit', methods: ['GET', 'POST'])]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_EDITOR')")]
    public function edit(Request $request, Subject $subject, EntityManagerInterface $em) : array|RedirectResponse {
        $editForm = $this->createForm(SubjectType::class, $subject);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The subject has been updated.');

            return $this->redirectToRoute('subject_show', ['id' => $subject->getId()]);
        }

        return [
            'subject' => $subject,
            'edit_form' => $editForm->createView(),
        ];
    }

    #[Route(path: '/{id}/delete', name: 'subject_delete', methods: ['GET'])]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    public function delete(Subject $subject, EntityManagerInterface $em) : RedirectResponse {
        $em->remove($subject);
        $em->flush();
        $this->addFlash('success', 'The subject was deleted.');

        return $this->redirectToRoute('subject_index');
    }
}
