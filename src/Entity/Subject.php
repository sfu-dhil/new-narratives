<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * Subject
 *
 * @ORM\Table(name="subject")
 * @ORM\Entity(repositoryClass="App\Repository\SubjectRepository")
 */
class Subject extends AbstractTerm {

    /**
     * @var SubjectSource
     * @ORM\ManyToOne(targetEntity="SubjectSource", inversedBy="subjects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $subjectSource;

    /**
     * @var Collection|Work[]
     * @ORM\ManyToMany(targetEntity="Work", mappedBy="subjects")
     */
    private $works;

    public function __construct() {
        parent::__construct();
        $this->works = new ArrayCollection();
    }


    /**
     * Set subjectSource
     *
     * @param SubjectSource $subjectSource
     *
     * @return Subject
     */
    public function setSubjectSource(SubjectSource $subjectSource)
    {
        $this->subjectSource = $subjectSource;

        return $this;
    }

    /**
     * Get subjectSource
     *
     * @return SubjectSource
     */
    public function getSubjectSource()
    {
        return $this->subjectSource;
    }

    /**
     * Add work
     *
     * @param Work $work
     *
     * @return Subject
     */
    public function addWork(Work $work)
    {
        $this->works[] = $work;

        return $this;
    }

    /**
     * Remove work
     *
     * @param Work $work
     */
    public function removeWork(Work $work)
    {
        $this->works->removeElement($work);
    }

    /**
     * Get works
     *
     * @return Collection
     */
    public function getWorks()
    {
        return $this->works;
    }
}
