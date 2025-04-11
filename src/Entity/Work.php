<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\WorkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Nines\MediaBundle\Entity\LinkableInterface;
use Nines\MediaBundle\Entity\LinkableTrait;
use Nines\UserBundle\Entity\User;
use Nines\UtilBundle\Entity\AbstractEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Config\Tradition;

#[ORM\Table(name: 'work')]
#[ORM\Index(columns: ['title'], flags: ['fulltext'])]
#[ORM\Index(columns: ['publication_place'], flags: ['fulltext'])]
#[ORM\Index(columns: ['dedication'], flags: ['fulltext'])]
#[ORM\Entity(repositoryClass: WorkRepository::class)]
class Work extends AbstractEntity implements LinkableInterface {
    use LinkableTrait {
        LinkableTrait::__construct as linkable_constructor;
    }

    #[ORM\Column(type: Types::TEXT)]
    private ?string $title = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $edition = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $volume = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    #[Assert\Language]
    private ?string $languageCode = null;

    #[ORM\Column(type: Types::STRING, enumType: Tradition::class, length: 255, nullable: true)]
    private ?Tradition $tradition = null;

    #[ORM\Column(type: Types::STRING, length: 200, nullable: true)]
    private ?string $publicationPlace = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $physicalDescription = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $illustrations = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $frontispiece = null;

    #[ORM\Column(type: Types::STRING, length: 600, nullable: true)]
    private ?string $translationDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $dedication = null;

    #[ORM\Column(type: Types::STRING, length: 200, nullable: true)]
    private ?string $worldcatUrl = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $transcription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $physicalLocations = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $digitalLocations = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $digitalUrl = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $citation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $editorialNotes = null;

    #[ORM\ManyToOne(targetEntity: Genre::class, inversedBy: 'works')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Genre $genre = null;

    #[ORM\ManyToOne(targetEntity: Publisher::class, inversedBy: 'works')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Publisher $publisher = null;

    #[ORM\ManyToOne(targetEntity: WorkCategory::class, inversedBy: 'works')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?WorkCategory $workCategory = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: false)]
    private bool $complete;

    /**
     * @var Collection<User>
     */
    #[ORM\JoinTable(name: 'work_checked_user')]
    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $checkedBy;

    /**
     * @var Collection<Contribution>
     */
    #[ORM\OneToMany(targetEntity: Contribution::class, mappedBy: 'work', cascade: ['persist'], orphanRemoval: true)]
    private Collection $contributions;

    /**
     * @var Collection<DateYear>
     */
    #[ORM\OneToMany(targetEntity: DateYear::class, mappedBy: 'work', cascade: ['persist'], orphanRemoval: true)]
    private Collection $dates;

    /**
     * @var Collection<Subject>
     */
    #[ORM\JoinTable(name: 'works_subjects')]
    #[ORM\ManyToMany(targetEntity: Subject::class, inversedBy: 'works')]
    private Collection $subjects;

    public function __construct() {
        parent::__construct();
        $this->linkable_constructor();
        $this->complete = false;
        $this->subjects = new ArrayCollection();
        $this->dates = new ArrayCollection();
        $this->contributions = new ArrayCollection();
        $this->checkedBy = new ArrayCollection();
    }

    public function __toString() : string {
        return (string) $this->title;
    }

    public function setTitle(?string $title) : self {
        $this->title = $title;

        return $this;
    }

    public function getTitle() : ?string {
        return $this->title;
    }

    public function setEdition(?int $edition) : self {
        $this->edition = $edition;

        return $this;
    }

    public function getEdition() : ?int {
        return $this->edition;
    }

    public function setVolume(?int $volume) : self {
        $this->volume = $volume;

        return $this;
    }

    public function getVolume() : ?int {
        return $this->volume;
    }

    public function getLanguageCode() : ?string {
        return $this->languageCode;
    }

    public function setLanguageCode(?string $languageCode) : self {
        $this->languageCode = $languageCode;

        return $this;
    }

    public function getTradition() : ?Tradition {
        return $this->tradition;
    }

    public function setTradition(?Tradition $tradition) : self {
        $this->tradition = $tradition;

        return $this;
    }

    public function setPublicationPlace(?string $publicationPlace) : self {
        $this->publicationPlace = $publicationPlace;

        return $this;
    }

    public function getPublicationPlace() : ?string {
        return $this->publicationPlace;
    }

    public function setPhysicalDescription(?string $physicalDescription) : self {
        $this->physicalDescription = $physicalDescription;

        return $this;
    }

    public function getPhysicalDescription() : ?string {
        return $this->physicalDescription;
    }

    public function setIllustrations(?bool $illustrations) : self {
        $this->illustrations = $illustrations;

        return $this;
    }

    public function getIllustrations() : ?bool {
        return $this->illustrations;
    }

    public function setFrontispiece(?bool $frontispiece) : self {
        $this->frontispiece = $frontispiece;

        return $this;
    }

    public function getFrontispiece() : ?bool {
        return $this->frontispiece;
    }

    public function setTranslationDescription(?string $translationDescription) : self {
        $this->translationDescription = $translationDescription;

        return $this;
    }

    public function getTranslationDescription() : ?string {
        return $this->translationDescription;
    }

    public function setDedication(?string $dedication) : self {
        $this->dedication = $dedication;

        return $this;
    }

    public function getDedication() : ?string {
        return $this->dedication;
    }

    public function setWorldcatUrl(?string $worldcatUrl) : self {
        $this->worldcatUrl = $worldcatUrl;

        return $this;
    }

    public function getWorldcatUrl() : ?string {
        return $this->worldcatUrl;
    }

    public function setTranscription(?bool $transcription) : self {
        $this->transcription = $transcription;

        return $this;
    }

    public function getTranscription() : ?bool {
        return $this->transcription;
    }

    public function setPhysicalLocations(?string $physicalLocations) : self {
        $this->physicalLocations = $physicalLocations;

        return $this;
    }

    public function getPhysicalLocations() : ?string {
        return $this->physicalLocations;
    }

    public function setDigitalLocations(?string $digitalLocations) : self {
        $this->digitalLocations = $digitalLocations;

        return $this;
    }

    public function getDigitalLocations() : ?string {
        return $this->digitalLocations;
    }

    public function setDigitalUrl(?string $digitalUrl) : self {
        $this->digitalUrl = $digitalUrl;

        return $this;
    }

    public function getDigitalUrl() : ?string {
        return $this->digitalUrl;
    }

    public function setNotes(?string $notes) : self {
        $this->notes = $notes;

        return $this;
    }

    public function getNotes() : ?string {
        return $this->notes;
    }

    public function setGenre(Genre $genre) : self {
        $this->genre = $genre;

        return $this;
    }

    public function getGenre() : ?Genre {
        return $this->genre;
    }

    public function setPublisher(?Publisher $publisher) : self {
        $this->publisher = $publisher;

        return $this;
    }

    public function getPublisher() : ?Publisher {
        return $this->publisher;
    }

    public function setWorkCategory(WorkCategory $workCategory) : self {
        $this->workCategory = $workCategory;

        return $this;
    }

    public function getWorkCategory() : ?WorkCategory {
        return $this->workCategory;
    }

    public function addContribution(Contribution $contribution) : self {
        if ( ! $this->contributions->contains($contribution)) {
            $this->contributions[] = $contribution;
            $contribution->setWork($this);
        }

        return $this;
    }

    public function removeContribution(Contribution $contribution) : void {
        $this->contributions->removeElement($contribution);
    }

    public function getContributions() : Collection {
        return $this->contributions;
    }

    public function addDate(DateYear $date) : self {
        if ( ! $this->dates->contains($date)) {
            $this->dates[] = $date;
            $date->setWork($this);
        }

        return $this;
    }

    public function removeDate(DateYear $date) : void {
        $this->dates->removeElement($date);
    }

    public function getDates() : Collection {
        return $this->dates;
    }

    public function setDates(null|array|Collection $dates) : self {
        if ( ! $dates) {
            $this->dates = new ArrayCollection();
        } else {
            if (is_array($dates)) {
                $this->dates = new ArrayCollection($dates);
            } else {
                $this->dates = $dates;
            }
        }

        return $this;
    }

    public function addSubject(Subject $subject) : self {
        $this->subjects[] = $subject;

        return $this;
    }

    public function removeSubject(Subject $subject) : void {
        $this->subjects->removeElement($subject);
    }

    public function getSubjects() : Collection {
        return $this->subjects;
    }

    public function setSubjects(null|array|Collection $subjects) : self {
        if ( ! $subjects) {
            $this->subjects = new ArrayCollection();
        } else {
            if (is_array($subjects)) {
                $this->subjects = new ArrayCollection($subjects);
            } else {
                $this->subjects = $subjects;
            }
        }

        return $this;
    }

    public function addCheckedBy(User $checkedBy) : self {
        if ( ! $this->checkedBy->contains($checkedBy)) {
            $this->checkedBy->add($checkedBy);
        }

        return $this;
    }

    public function removeCheckedBy(User $checkedBy) : void {
        $this->checkedBy->removeElement($checkedBy);
    }

    public function getCheckedBy() : Collection {
        return $this->checkedBy;
    }

    public function getFirstContribution() : ?Contribution {
        foreach ($this->contributions as $contribution) {
            if ('aut' === $contribution->getRole()->getName()) {
                return $contribution;
            }
        }
        if (count($this->contributions) > 0) {
            return $this->contributions[0];
        }

        return null;
    }

    public function setEditorialNotes(?string $editorialNotes) : self {
        $this->editorialNotes = $editorialNotes;

        return $this;
    }

    public function getEditorialNotes() : ?string {
        return $this->editorialNotes;
    }

    public function setComplete(bool $complete) : self {
        $this->complete = $complete;

        return $this;
    }

    public function getComplete() : bool {
        return $this->complete;
    }

    public function getCitation() : ?string {
        return $this->citation;
    }

    public function setCitation(?string $citation) : void {
        $this->citation = $citation;
    }
}
