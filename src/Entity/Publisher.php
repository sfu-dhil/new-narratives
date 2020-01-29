<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Publisher
 *
 * @ORM\Table(name="publisher", indexes={
 *  @ORM\Index(columns={"name"}, flags={"fulltext"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\PublisherRepository")
 */
class Publisher extends AbstractEntity {

    /**
     * @var string
     * @ORM\Column(type="string", length=600)
     */
    private $name;

    /**
     * @var Collection|Work[]
     * @ORM\OneToMany(targetEntity="Work", mappedBy="publisher")
     */
    private $works;

    public function __construct() {
        parent::__construct();
        $this->works = new ArrayCollection();
    }

    /**
     * Return a string representation.
     *
     * @return string
     */
    public function __toString() {
        return $this->name;
    }


    /**
     * Add work
     *
     * @param Work $work
     *
     * @return Publisher
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

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Publisher
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
