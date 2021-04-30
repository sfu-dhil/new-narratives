<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Publisher.
 *
 * @ORM\Table(name="publisher", indexes={
 *     @ORM\Index(columns={"name"}, flags={"fulltext"})
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
     */
    public function __toString() : string {
        return $this->name;
    }

    /**
     * Add work.
     *
     * @return Publisher
     */
    public function addWork(Work $work) {
        $this->works[] = $work;

        return $this;
    }

    /**
     * Remove work.
     */
    public function removeWork(Work $work) : void {
        $this->works->removeElement($work);
    }

    /**
     * Get works.
     *
     * @return Collection
     */
    public function getWorks() {
        return $this->works;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Publisher
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }
}
