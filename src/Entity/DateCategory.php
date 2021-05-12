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
 * DateCategory.
 *
 * @ORM\Table(name="date_category")
 * @ORM\Entity(repositoryClass="App\Repository\DateCategoryRepository")
 */
class DateCategory extends AbstractTerm {
    /**
     * @var Collection|DateYear[]
     * @ORM\OneToMany(targetEntity="DateYear", mappedBy="dateCategory")
     */
    private $dates;

    public function __construct() {
        parent::__construct();
        $this->dates = new ArrayCollection();
    }

    /**
     * Add date.
     *
     * @return DateCategory
     */
    public function addDate(DateYear $date) {
        $this->dates[] = $date;

        return $this;
    }

    /**
     * Remove date.
     */
    public function removeDate(DateYear $date) : void {
        $this->dates->removeElement($date);
    }

    /**
     * Get dates.
     *
     * @return Collection
     */
    public function getDates() {
        return $this->dates;
    }
}
