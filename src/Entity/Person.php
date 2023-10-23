<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Nines\MediaBundle\Entity\LinkableInterface;
use Nines\MediaBundle\Entity\LinkableTrait;
use Nines\UtilBundle\Entity\AbstractEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'person')]
#[ORM\Index(columns: ['full_name'], flags: ['fulltext'])]
#[ORM\Entity(repositoryClass: PersonRepository::class)]
class Person extends AbstractEntity implements LinkableInterface {
    use LinkableTrait {
        LinkableTrait::__construct as linkable_constructor;
    }

    #[ORM\Column(type: Types::STRING, length: 200)]
    #[Groups(['shallow'])]
    private ?string $fullName = null;

    #[ORM\Column(type: Types::STRING, length: 12, nullable: true)]
    #[Assert\Regex(pattern: '/^\d{4}(?:-\d{2}-\d{2})?$/', message: '{{ value }} is not a valid value. It must be formatted as a year YYYY or a date YYYY-MM-DD.')]
    private ?string $birthDate = null;

    #[ORM\Column(type: Types::STRING, length: 12, nullable: true)]
    #[Assert\Regex(pattern: '/^\d{4}(?:-\d{2}-\d{2})?$/', message: '{{ value }} is not a valid value. It must be formatted as a year YYYY or a date YYYY-MM-DD.')]
    private ?string $deathDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $biography = null;

    #[ORM\ManyToOne(targetEntity: Place::class, inversedBy: 'peopleBorn')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Place $birthPlace = null;

    #[ORM\ManyToOne(targetEntity: Place::class, inversedBy: 'peopleDied')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Place $deathPlace = null;

    /**
     * @var Collection<Place>
     */
    #[ORM\ManyToMany(targetEntity: Place::class, inversedBy: 'residents')]
    private Collection $residences;

    /**
     * @var Collection<Contribution>
     */
    #[ORM\OneToMany(targetEntity: Contribution::class, mappedBy: 'person')]
    #[Groups(['contributions'])]
    private Collection $contributions;

    public function __construct() {
        parent::__construct();
        $this->linkable_constructor();
        $this->contributions = new ArrayCollection();
        $this->residences = new ArrayCollection();
    }

    public function __toString() : string {
        return (string) $this->fullName;
    }

    public function setFullName(?string $fullName) : self {
        $this->fullName = $fullName;

        return $this;
    }

    public function getFullName() : ?string {
        return $this->fullName;
    }

    public function addContribution(Contribution $contribution) : self {
        $this->contributions[] = $contribution;

        return $this;
    }

    public function removeContribution(Contribution $contribution) : void {
        $this->contributions->removeElement($contribution);
    }

    public function getContributions() : Collection {
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
