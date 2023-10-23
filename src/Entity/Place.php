<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

#[ORM\Table(name: 'place')]
#[ORM\Index(name: 'place_names_ft', columns: ['name'], flags: ['fulltext'])]
#[ORM\Entity(repositoryClass: PlaceRepository::class)]
class Place extends AbstractEntity {
    #[ORM\Column(name: 'name', type: Types::STRING, length: 200, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(name: 'state', type: Types::STRING, length: 200, nullable: true)]
    private ?string $state = null;

    #[ORM\Column(name: 'country', type: Types::STRING, length: 200, nullable: false)]
    private ?string $country = null;

    /**
     * @var Collection<Person>
     */
    #[ORM\OneToMany(targetEntity: Person::class, mappedBy: 'birthPlace')]
    private Collection $peopleBorn;

    /**
     * @var Collection<Person>
     */
    #[ORM\OneToMany(targetEntity: Person::class, mappedBy: 'deathPlace')]
    private Collection $peopleDied;

    /**
     * @var Collection<Person>
     */
    #[ORM\ManyToMany(targetEntity: Person::class, mappedBy: 'residences')]
    private Collection $residents;

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

    public function setName(string $name) : self {
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

    public function setCountry(string $country) : self {
        $this->country = $country;

        return $this;
    }

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
