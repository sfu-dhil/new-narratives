<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\MediaBundle\Entity\ImageContainerInterface;
use Nines\MediaBundle\Entity\ImageContainerTrait;
use Nines\MediaBundle\Entity\LinkableInterface;
use Nines\MediaBundle\Entity\LinkableTrait;
use Nines\UtilBundle\Entity\AbstractEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Person.
 *
 * @ORM\Table(name="person", indexes={
 *     @ORM\Index(columns={"full_name"}, flags={"fulltext"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person extends AbstractEntity implements LinkableInterface, ImageContainerInterface {
    use LinkableTrait {
        LinkableTrait::__construct as linkable_constructor;

    }
    use ImageContainerTrait {
        ImageContainerTrait::__construct as imagecontainer_constructor;

    }

    /**
     * @var string
     * @ORM\Column(type="string", length=200)
     * @Groups({"shallow"})
     */
    private $fullName;

    /**
     * @var string
     * @ORM\Column(type="string", length=12, nullable=true)
     * @Assert\Regex(
     *     pattern="/^\d{4}(?:-\d{2}-\d{2})?$/",
     * message="{{ value }} is not a valid value. It must be formatted as a year YYYY or a date YYYY-MM-DD.")
     */
    private $birthDate;

    /**
     * @var string
     * @ORM\Column(type="string", length=12, nullable=true)
     * @Assert\Regex(
     *     pattern="/^\d{4}(?:-\d{2}-\d{2})?$/",
     * message="{{ value }} is not a valid value. It must be formatted as a year YYYY or a date YYYY-MM-DD.")
     */
    private $deathDate;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $biography;

    /**
     * @var Place
     * @ORM\ManyToOne(targetEntity="App\Entity\Place", inversedBy="peopleBorn")
     */
    private $birthPlace;

    /**
     * @var Place
     * @ORM\ManyToOne(targetEntity="App\Entity\Place", inversedBy="peopleDied")
     */
    private $deathPlace;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="App\Entity\Place", inversedBy="residents")
     */
    private $residences;

    /**
     * @var Collection|Contribution[]
     * @ORM\OneToMany(targetEntity="Contribution", mappedBy="person")
     * @Groups({"contributions"})
     */
    private $contributions;

    public function __construct() {
        parent::__construct();
        $this->linkable_constructor();
        $this->imagecontainer_constructor();
        $this->contributions = new ArrayCollection();
        $this->residences = new ArrayCollection();
    }

    /**
     * Return a string representation.
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

    public function getBirthDate() : ?string {
        return $this->birthDate;
    }

    public function setBirthDate(?string $birthDate) : self {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getDeathDate() : ?string {
        return $this->deathDate;
    }

    public function setDeathDate(?string $deathDate) : self {
        $this->deathDate = $deathDate;

        return $this;
    }

    public function getBiography() : ?string {
        return $this->biography;
    }

    public function setBiography(?string $biography) : self {
        $this->biography = $biography;

        return $this;
    }

    public function getBirthPlace() : ?Place {
        return $this->birthPlace;
    }

    public function setBirthPlace(?Place $birthPlace) : self {
        $this->birthPlace = $birthPlace;

        return $this;
    }

    public function getDeathPlace() : ?Place {
        return $this->deathPlace;
    }

    public function setDeathPlace(?Place $deathPlace) : self {
        $this->deathPlace = $deathPlace;

        return $this;
    }

    /**
     * @return Collection|Place[]
     */
    public function getResidences() : Collection {
        return $this->residences;
    }

    public function addResidence(Place $residence) : self {
        if ( ! $this->residences->contains($residence)) {
            $this->residences[] = $residence;
        }

        return $this;
    }

    public function removeResidence(Place $residence) : self {
        $this->residences->removeElement($residence);

        return $this;
    }
}
