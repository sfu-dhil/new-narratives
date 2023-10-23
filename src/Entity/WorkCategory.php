<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\WorkCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

#[ORM\Table(name: 'work_type')]
#[ORM\Entity(repositoryClass: WorkCategoryRepository::class)]
class WorkCategory extends AbstractTerm {
    /**
     * @var Collection<Work>
     */
    #[ORM\OneToMany(targetEntity: Work::class, mappedBy: 'workCategory')]
    private Collection $works;

    public function __construct() {
        parent::__construct();
        $this->works = new ArrayCollection();
    }

    public function addWork(Work $work) : self {
        $this->works[] = $work;

        return $this;
    }

    public function removeWork(Work $work) : void {
        $this->works->removeElement($work);
    }

    public function getWorks() : Collection {
        return $this->works;
    }
}
