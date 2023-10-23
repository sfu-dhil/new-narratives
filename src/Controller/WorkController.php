<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Work;
use App\Form\WorkContributionsType;
use App\Form\WorkDatesType;
use App\Form\WorkSearchType;
use App\Form\WorkType;
use App\Repository\WorkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/work')]
class WorkController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    #[Route(path: '/', name: 'work_index', methods: ['GET'])]
    #[Template]
    public function index(Request $request, EntityManagerInterface $em) : array {
        $dql = 'SELECT e FROM App:Work e ORDER BY e.id';
        $query = $em->createQuery($dql);

        $works = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);

        return [
            'works' => $works,
        ];
    }

    #[Route(path: '/search', name: 'work_search', methods: ['GET'])]
    #[Template]
    public function search(Request $request, WorkRepository $repo) : array {
        $works = [];
        $form = $this->createForm(WorkSearchType::class, null, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $query = $repo->searchQuery($form->getData());

            $works = $this->paginator->paginate($query, $request->query->getInt('page', 1), 25);
        }

        return [
            'works' => $works,
            'form' => $form->createView(),
        ];
    }

    #[Route(path: '/new', name: 'work_new', methods: ['GET', 'POST'])]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_EDITOR')")]
    public function new(Request $request, EntityManagerInterface $em) : array|RedirectResponse {
        $work = new Work();
        $form = $this->createForm(WorkType::class, $work);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($work);
            $em->flush();

            $this->addFlash('success', 'The new work was created.');

            return $this->redirectToRoute('work_show', ['id' => $work->getId()]);
        }

        return [
            'work' => $work,
            'form' => $form->createView(),
        ];
    }

    #[Route(path: '/{id}', name: 'work_show', methods: ['GET'])]
    #[Template]
    public function show(Work $work) : array {
        return [
            'work' => $work,
        ];
    }

    #[Route(path: '/{id}/edit', name: 'work_edit', methods: ['GET', 'POST'])]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_EDITOR')")]
    public function edit(Request $request, Work $work, EntityManagerInterface $em) : array|RedirectResponse {
        $editForm = $this->createForm(WorkType::class, $work);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The work has been updated.');

            return $this->redirectToRoute('work_show', ['id' => $work->getId()]);
        }

        return [
            'work' => $work,
            'edit_form' => $editForm->createView(),
        ];
    }

    #[Route(path: '/{id}/delete', name: 'work_delete', methods: ['GET'])]
    #[Security("is_granted('ROLE_CONTENT_EDITOR')")]
    public function delete(Work $work, EntityManagerInterface $em) : RedirectResponse {
        $em->remove($work);
        $em->flush();
        $this->addFlash('success', 'The work was deleted.');

        return $this->redirectToRoute('work_index');
    }

    #[Route(path: '/{id}/dates', name: 'work_dates')]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_EDITOR')")]
    public function workDates(Request $request, Work $work, EntityManagerInterface $em) : array|RedirectResponse {
        $form = $this->createForm(WorkDatesType::class, $work);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The dates have been updated.');

            return $this->redirectToRoute('work_show', ['id' => $work->getId()]);
        }

        return [
            'work' => $work,
            'form' => $form->createView(),
        ];
    }

    #[Route(path: '/{id}/contributions', name: 'work_contributions')]
    #[Template]
    #[Security("is_granted('ROLE_CONTENT_ADMIN')")]
    public function workContributions(Request $request, Work $work, EntityManagerInterface $em) : array|RedirectResponse {
        $form = $this->createForm(WorkContributionsType::class, $work);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The contributions have been updated.');

            return $this->redirectToRoute('work_show', ['id' => $work->getId()]);
        }

        return [
            'work' => $work,
            'form' => $form->createView(),
        ];
    }
}
