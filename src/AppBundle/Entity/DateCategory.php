<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * DateCategory
 *
 * @ORM\Table(name="date_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DateCategoryRepository")
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
     * Add date
     *
     * @param DateYear $date
     *
     * @return DateCategory
     */
    public function addDate(DateYear $date)
    {
        $this->dates[] = $date;

        return $this;
    }

    /**
     * Remove date
     *
     * @param DateYear $date
     */
    public function removeDate(DateYear $date)
    {
        $this->dates->removeElement($date);
    }

    /**
     * Get dates
     *
     * @return Collection
     */
    public function getDates()
    {
        return $this->dates;
    }
}
