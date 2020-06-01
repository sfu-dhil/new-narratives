<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Person.
 *
 * @ORM\Table(name="person", indexes={
 *  @ORM\Index(columns={"full_name"}, flags={"fulltext"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person extends AbstractEntity {
    /**
     * @var string
     * @ORM\Column(type="string", length=200)
     * @Groups({"shallow"})
     */
    private $fullName;

    /**
     * @var Collection|Contribution[]
     * @ORM\OneToMany(targetEntity="Contribution", mappedBy="person")
     * @Groups({"contributions"})
     */
    private $contributions;

    public function __construct() {
        parent::__construct();
        $this->contributions = new ArrayCollection();
    }

    /**
     * Return a string representation.
     *
     * @return string
     */
    public function __toString() : string {
        return $this->fullName;
    }

    /**
     * Set fullName.
     *
     * @param string $fullName
     *
     * @return Person
     */
    public function setFullName($fullName) {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName.
     *
     * @return string
     */
    public function getFullName() {
        return $this->fullName;
    }

    /**
     * Add contribution.
     *
     * @return Person
     */
    public function addContribution(Contribution $contribution) {
        $this->contributions[] = $contribution;

        return $this;
    }

    /**
     * Remove contribution.
     */
    public function removeContribution(Contribution $contribution) : void {
        $this->contributions->removeElement($contribution);
    }

    /**
     * Get contributions.
     *
     * @return Collection
     */
    public function getContributions() {
        return $this->contributions;
    }
}
