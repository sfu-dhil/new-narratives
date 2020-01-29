<?php

namespace App\Controller;

use App\Entity\SubjectSource;
use App\Form\SubjectSourceType;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * SubjectSource controller.
 *
 * @Route("/subject_source")
 */
class SubjectSourceController extends AbstractController  implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * Lists all SubjectSource entities.
     *
     * @Route("/", name="subject_source_index", methods={"GET"})
     *
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request, EntityManagerInterface $em) {
        $dql = 'SELECT e FROM App:SubjectSource e ORDER BY e.id';
        $query = $em->createQuery($dql);
        ;
        $subjectSources = $this->paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'subjectSources' => $subjectSources,
        );
    }

    /**
     * Creates a new SubjectSource entity.
     *
     * @Route("/new", name="subject_source_new", methods={"GET","POST"})
     *
     * @Template()
     * @Security("has_role('ROLE_CONTENT_EDITOR')")
     *
     * @param Request $request
     */
    public function newAction(Request $request, EntityManagerInterface $em) {
        $subjectSource = new SubjectSource();
        $form = $this->createForm(SubjectSourceType::class, $subjectSource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($subjectSource);
            $em->flush();

            $this->addFlash('success', 'The new subjectSource was created.');
            return $this->redirectToRoute('subject_source_show', array('id' => $subjectSource->getId()));
        }

        return array(
            'subjectSource' => $subjectSource,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a SubjectSource entity.
     *
     * @Route("/{id}", name="subject_source_show", methods={"GET"})
     *
     * @Template()
     * @param SubjectSource $subjectSource
     */
    public function showAction(SubjectSource $subjectSource) {

        return array(
            'subjectSource' => $subjectSource,
        );
    }

    /**
     * Displays a form to edit an existing SubjectSource entity.
     *
     * @Route("/{id}/edit", name="subject_source_edit", methods={"GET","POST"})
     *
     * @Template()
     * @Security("has_role('ROLE_CONTENT_EDITOR')")
     *
     * @param Request $request
     * @param SubjectSource $subjectSource
     */
    public function editAction(Request $request, SubjectSource $subjectSource, EntityManagerInterface $em) {
        $editForm = $this->createForm(SubjectSourceType::class, $subjectSource);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'The subjectSource has been updated.');
            return $this->redirectToRoute('subject_source_show', array('id' => $subjectSource->getId()));
        }

        return array(
            'subjectSource' => $subjectSource,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a SubjectSource entity.
     *
     * @Route("/{id}/delete", name="subject_source_delete", methods={"GET"})
     *
     * @Security("has_role('ROLE_CONTENT_ADMIN')")
     *
     * @param Request $request
     * @param SubjectSource $subjectSource
     */
    public function deleteAction(Request $request, SubjectSource $subjectSource, EntityManagerInterface $em) {
        $em->remove($subjectSource);
        $em->flush();
        $this->addFlash('success', 'The subjectSource was deleted.');

        return $this->redirectToRoute('subject_source_index');
    }

}
