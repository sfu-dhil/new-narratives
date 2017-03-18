<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Contribution
 *
 * @ORM\Table(name="contribution")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContributionRepository")
 */
class Contribution extends AbstractEntity {

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
     * 
     * @return string
     */
    public function __toString() {
        return $this->id;
    }


    /**
     * Set work
     *
     * @param \AppBundle\Entity\Work $work
     *
     * @return Contribution
     */
    public function setWork(\AppBundle\Entity\Work $work)
    {
        $this->work = $work;

        return $this;
    }

    /**
     * Get work
     *
     * @return \AppBundle\Entity\Work
     */
    public function getWork()
    {
        return $this->work;
    }

    /**
     * Set role
     *
     * @param \AppBundle\Entity\Role $role
     *
     * @return Contribution
     */
    public function setRole(\AppBundle\Entity\Role $role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \AppBundle\Entity\Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set person
     *
     * @param \AppBundle\Entity\Person $person
     *
     * @return Contribution
     */
    public function setPerson(\AppBundle\Entity\Person $person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \AppBundle\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }
}
