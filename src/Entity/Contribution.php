<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Contribution.
 *
 * @ORM\Table(name="contribution")
 * @ORM\Entity(repositoryClass="App\Repository\ContributionRepository")
 */
class Contribution extends AbstractEntity
{
    /**
     * @var Work
     * @ORM\ManyToOne(targetEntity="Work", inversedBy="contributions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $work;

    /**
     * @var Role
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="contributions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @var Person
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="contributions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $person;

    /**
     * Return a string representation.
     */
    public function __toString() : string {
        return "{$this->role->getLabel()} . {$this->person->getFullName()}";
    }

    /**
     * Set work.
     *
     * @param \App\Entity\Work $work
     *
     * @return Contribution
     */
    public function setWork(Work $work) {
        $this->work = $work;

        return $this;
    }

    /**
     * Get work.
     *
     * @return \App\Entity\Work
     */
    public function getWork() {
        return $this->work;
    }

    /**
     * Set role.
     *
     * @param \App\Entity\Role $role
     *
     * @return Contribution
     */
    public function setRole(Role $role) {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role.
     *
     * @return \App\Entity\Role
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * Set person.
     *
     * @param \App\Entity\Person $person
     *
     * @return Contribution
     */
    public function setPerson(Person $person) {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person.
     *
     * @return \App\Entity\Person
     */
    public function getPerson() {
        return $this->person;
    }
}
