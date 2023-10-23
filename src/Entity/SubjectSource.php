<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SubjectSourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

#[ORM\Table(name: 'subject_source')]
#[ORM\Entity(repositoryClass: SubjectSourceRepository::class)]
class SubjectSource extends AbstractTerm {
    /**
     * @var Collection<Subject>
     */
    #[ORM\OneToMany(targetEntity: Subject::class, mappedBy: 'subjectSource')]
    private Collection $subjects;

    public function __construct() {
        parent::__construct();
        $this->subjects = new ArrayCollection();
    }

    public function addSubject(Subject $subject) : self {
        $this->subjects[] = $subject;

        return $this;
    }

    public function removeSubject(Subject $subject) : void {
        $this->subjects->removeElement($subject);
    }

    public function getSubjects() : Collection {
        return $this->subjects;
    }
}
