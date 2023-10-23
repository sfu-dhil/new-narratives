<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ContributionRepository;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

#[ORM\Table(name: 'contribution')]
#[ORM\Entity(repositoryClass: ContributionRepository::class)]
class Contribution extends AbstractEntity {
    #[ORM\ManyToOne(targetEntity: Work::class, inversedBy: 'contributions')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Work $work = null;

    #[ORM\ManyToOne(targetEntity: Role::class, inversedBy: 'contributions')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Role $role = null;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'contributions')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Person $person = null;

    public function __toString() : string {
        return "{$this->role->getLabel()} . {$this->person->getFullName()}";
    }

    public function setWork(Work $work) : self {
        $this->work = $work;

        return $this;
    }

    public function getWork() : ?Work {
        return $this->work;
    }

    public function setRole(Role $role) : self {
        $this->role = $role;

        return $this;
    }

    public function getRole() : ?Role {
        return $this->role;
    }

    public function setPerson(Person $person) : self {
        $this->person = $person;

        return $this;
    }

    public function getPerson() : ?Person {
        return $this->person;
    }
}
