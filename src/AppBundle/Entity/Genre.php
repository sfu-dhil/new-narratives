<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * Genre
 *
 * @ORM\Table(name="genre")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GenreRepository")
 */
class Genre extends AbstractTerm {

    /**
     * @var Collection|Work[]
     * @ORM\OneToMany(targetEntity="Work", mappedBy="genre")
     */
    private $works;

    public function __construct() {
        parent::__construct();
        $this->works = new ArrayCollection();
    }

    /**
     * Add work
     *
     * @param Work $work
     *
     * @return Genre
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
