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
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * Genre.
 *
 * @ORM\Table(name="genre")
 * @ORM\Entity(repositoryClass="App\Repository\GenreRepository")
 */
class Genre extends AbstractTerm
{
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
     * Add work.
     *
     * @return Genre
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
}
