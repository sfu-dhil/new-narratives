<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * SubjectSource.
 *
 * @ORM\Table(name="subject_source")
 * @ORM\Entity(repositoryClass="App\Repository\SubjectSourceRepository")
 */
class SubjectSource extends AbstractTerm {
    /**
     * @var Collection|Subject[]
     * @ORM\OneToMany(targetEntity="Subject", mappedBy="subjectSource")
     */
    private $subjects;

    public function __construct() {
        parent::__construct();
        $this->subjects = new ArrayCollection();
    }

    /**
     * Add subject.
     *
     * @return SubjectSource
     */
    public function addSubject(Subject $subject) {
        $this->subjects[] = $subject;

        return $this;
    }

    /**
     * Remove subject.
     */
    public function removeSubject(Subject $subject) : void {
        $this->subjects->removeElement($subject);
    }

    /**
     * Get subjects.
     *
     * @return Collection
     */
    public function getSubjects() {
        return $this->subjects;
    }
}
