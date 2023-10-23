<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DateCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

#[ORM\Table(name: 'date_category')]
#[ORM\Entity(repositoryClass: DateCategoryRepository::class)]
class DateCategory extends AbstractTerm {
    /**
     * @var Collection<DateYear>
     */
    #[ORM\OneToMany(targetEntity: DateYear::class, mappedBy: 'dateCategory')]
    private Collection $dates;

    public function __construct() {
        parent::__construct();
        $this->dates = new ArrayCollection();
    }

    public function addDate(DateYear $date) : self {
        $this->dates[] = $date;

        return $this;
    }

    public function removeDate(DateYear $date) : void {
        $this->dates->removeElement($date);
    }

    public function getDates() : ?Collection {
        return $this->dates;
    }
}
