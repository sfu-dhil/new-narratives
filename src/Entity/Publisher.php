<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PublisherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

#[ORM\Table(name: 'publisher')]
#[ORM\Index(columns: ['name'], flags: ['fulltext'])]
#[ORM\Entity(repositoryClass: PublisherRepository::class)]
class Publisher extends AbstractEntity {
    #[ORM\Column(type: Types::STRING, length: 600)]
    private ?string $name = null;

    /**
     * @var Collection<Work>
     */
    #[ORM\OneToMany(targetEntity: Work::class, mappedBy: 'publisher')]
    private Collection $works;

    public function __construct() {
        parent::__construct();
        $this->works = new ArrayCollection();
    }

    public function __toString() : string {
        return (string) $this->name;
    }

    public function addWork(Work $work) : self {
        $this->works[] = $work;

        return $this;
    }

    public function removeWork(Work $work) : void {
        $this->works->removeElement($work);
    }

    public function getWorks() : Collection {
        return $this->works;
    }

    public function setName(?string $name) : self {
        $this->name = $name;

        return $this;
    }

    public function getName() : ?string {
        return $this->name;
    }
}
