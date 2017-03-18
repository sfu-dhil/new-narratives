<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Person
 *
 * @ORM\Table(name="person")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PersonRepository")
 */
class Person extends AbstractEntity {

    /**
     * @var string
     * @ORM\Column(type="string", length=200)
     */
    private $fullname;
    
    /**
     * @var Collection|Contribution[]
     * @ORM\OneToMany(targetEntity="Contribution", mappedBy="person")
     */
    private $contributions;
    
    public function __construct() {
        $this->contributions = new ArrayCollection();
    }
    
    /**
     * Return a string representation.
     * 
     * @return string
     */
    public function __toString() {
        return $this->fullname;
    }


    /**
     * Set fullname
     *
     * @param string $fullname
     *
     * @return Person
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Add contribution
     *
     * @param Contribution $contribution
     *
     * @return Person
     */
    public function addContribution(Contribution $contribution)
    {
        $this->contributions[] = $contribution;

        return $this;
    }

    /**
     * Remove contribution
     *
     * @param Contribution $contribution
     */
    public function removeContribution(Contribution $contribution)
    {
        $this->contributions->removeElement($contribution);
    }

    /**
     * Get contributions
     *
     * @return Collection
     */
    public function getContributions()
    {
        return $this->contributions;
    }
}
