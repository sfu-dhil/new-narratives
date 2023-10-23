<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

#[ORM\Table(name: 'genre')]
#[ORM\Entity(repositoryClass: GenreRepository::class)]
class Genre extends AbstractTerm {
    /**
     * @var Collection<Work>
     */
    #[ORM\OneToMany(targetEntity: Work::class, mappedBy: 'genre')]
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
