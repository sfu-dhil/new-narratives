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

/**
 * @ORM\Table(name="place",
 *     indexes={
 *         @ORM\Index(name="place_names_ft", columns={"name"}, flags={"fulltext"})
 *     })
 *     @ORM\Entity(repositoryClass="App\Repository\PlaceRepository")
 */
class Place extends AbstractEntity {
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=200, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=200, nullable=true)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=200, nullable=false)
     */
    private $country;

    /**
     * @var Collection|Person[]
     * @ORM\OneToMany(targetEntity="Person", mappedBy="birthPlace")
     */
    private $peopleBorn;

    /**
     * @var Collection|Person[]
     * @ORM\OneToMany(targetEntity="Person", mappedBy="deathPlace")
     */
    private $peopleDied;

    /**
     * @var Collection|Person[]
     * @ORM\ManyToMany(targetEntity="Person", mappedBy="residences")
     */
    private $residents;

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->peopleBorn = new ArrayCollection();
        $this->peopleDied = new ArrayCollection();
        $this->residents = new ArrayCollection();
    }

    /**
     * Return the name and country of this place.
     */
    public function __toString() : string {
        return $this->name . ' (' . $this->country . ')';
    }

    public function getName() : ?string {
        return $this->name;
    }

    public function setName(?string $name) : self {
        $this->name = $name;

        return $this;
    }

    public function getState() : ?string {
        return $this->state;
    }

    public function setState(?string $state) : self {
        $this->state = $state;

        return $this;
    }

    public function getCountry() : ?string {
        return $this->country;
    }

    public function setCountry(?string $country) : self {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getPeopleBorn() : Collection {
        return $this->peopleBorn;
    }

    public function addPeopleBorn(Person $peopleBorn) : self {
        if ( ! $this->peopleBorn->contains($peopleBorn)) {
            $this->peopleBorn[] = $peopleBorn;
            $peopleBorn->setBirthPlace($this);
        }

        return $this;
    }

    public function removePeopleBorn(Person $peopleBorn) : self {
        if ($this->peopleBorn->removeElement($peopleBorn)) {
            // set the owning side to null (unless already changed)
            if ($peopleBorn->getBirthPlace() === $this) {
                $peopleBorn->setBirthPlace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getPeopleDied() : Collection {
        return $this->peopleDied;
    }

    public function addPeopleDied(Person $peopleDied) : self {
        if ( ! $this->peopleDied->contains($peopleDied)) {
            $this->peopleDied[] = $peopleDied;
            $peopleDied->setDeathPlace($this);
        }

        return $this;
    }

    public function removePeopleDied(Person $peopleDied) : self {
        if ($this->peopleDied->removeElement($peopleDied)) {
            // set the owning side to null (unless already changed)
            if ($peopleDied->getDeathPlace() === $this) {
                $peopleDied->setDeathPlace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getResidents() : Collection {
        return $this->residents;
    }

    public function addResident(Person $resident) : self {
        if ( ! $this->residents->contains($resident)) {
            $this->residents[] = $resident;
            $resident->addResidence($this);
        }

        return $this;
    }

    public function removeResident(Person $resident) : self {
        if ($this->residents->removeElement($resident)) {
            $resident->removeResidence($this);
        }

        return $this;
    }
}
