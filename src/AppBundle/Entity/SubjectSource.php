<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * SubjectSource
 *
 * @ORM\Table(name="subject_source")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubjectSourceRepository")
 */
class SubjectSource extends AbstractTerm {
    
    /**
     * @var Collection|Subject[]
     * @ORM\OneToMany(targetEntity="Subject", mappedBy="subjectSource")
     */
    private $subjects;
    
    public function __construct() {
        $this->subjects = new ArrayCollection();
    }

    /**
     * Add subject
     *
     * @param Subject $subject
     *
     * @return SubjectSource
     */
    public function addSubject(Subject $subject)
    {
        $this->subjects[] = $subject;

        return $this;
    }

    /**
     * Remove subject
     *
     * @param Subject $subject
     */
    public function removeSubject(Subject $subject)
    {
        $this->subjects->removeElement($subject);
    }

    /**
     * Get subjects
     *
     * @return Collection
     */
    public function getSubjects()
    {
        return $this->subjects;
    }
}
