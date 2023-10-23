<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SubjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

#[ORM\Table(name: 'subject')]
#[ORM\Entity(repositoryClass: SubjectRepository::class)]
class Subject extends AbstractTerm {
    #[ORM\ManyToOne(targetEntity: SubjectSource::class, inversedBy: 'subjects')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?SubjectSource $subjectSource = null;

    /**
     * @var Collection<Work>
     */
    #[ORM\ManyToMany(targetEntity: Work::class, mappedBy: 'subjects')]
    private Collection $works;

    public function __construct() {
        parent::__construct();
        $this->works = new ArrayCollection();
    }

    public function setSubjectSource(SubjectSource $subjectSource) : self {
        $this->subjectSource = $subjectSource;

        return $this;
    }

    public function getSubjectSource() : ?SubjectSource {
        return $this->subjectSource;
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
