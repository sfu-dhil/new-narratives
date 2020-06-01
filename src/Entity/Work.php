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
use Nines\UserBundle\Entity\User;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Work.
 *
 * @ORM\Table(name="work", indexes={
 *  @ORM\Index(columns={"title"}, flags={"fulltext"}),
 *  @ORM\Index(columns={"publication_place"}, flags={"fulltext"}),
 *  @ORM\Index(columns={"dedication"}, flags={"fulltext"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\WorkRepository")
 */
class Work extends AbstractEntity {
    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $title;

    /**
     * @var bool
     * @ORM\Column(type="integer", nullable=true)
     */
    private $edition;

    /**
     * @var bool
     * @ORM\Column(type="integer", nullable=true)
     */
    private $volume;

    /**
     * @var string
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $publicationPlace;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $physicalDescription;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $illustrations;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $frontispiece;

    /**
     * @var string
     * @ORM\Column(type="string", length=600, nullable=true)
     */
    private $translationDescription;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $dedication;

    /**
     * @var string
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $worldcatUrl;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $transcription;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $physicalLocations;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $digitalLocations;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $digitalUrl;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $editorialNotes;

    /**
     * @var Genre
     * @ORM\ManyToOne(targetEntity="Genre", inversedBy="works")
     */
    private $genre;

    /**
     * @var Publisher
     * @ORM\ManyToOne(targetEntity="Publisher", inversedBy="works")
     */
    private $publisher;

    /**
     * @var WorkCategory
     * @ORM\ManyToOne(targetEntity="WorkCategory", inversedBy="works")
     * @ORM\JoinColumn(nullable=false)
     */
    private $workCategory;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $complete;

    /**
     * @var Collection|User[]
     * @ORM\ManyToMany(targetEntity="Nines\UserBundle\Entity\User")
     * @ORM\JoinTable(name="work_checked_user")
     */
    private $checkedBy;

    /**
     * @var Collection|Contribution[]
     * @ORM\OneToMany(targetEntity="Contribution", mappedBy="work", cascade={"persist"}, orphanRemoval=true)
     */
    private $contributions;

    /**
     * @var Collection|DateYear[]
     * @ORM\OneToMany(targetEntity="DateYear", mappedBy="work", cascade={"persist"}, orphanRemoval=true)
     */
    private $dates;

    /**
     * @var Collection|Subject[]
     * @ORM\ManyToMany(targetEntity="Subject", inversedBy="works")
     * @ORM\JoinTable(name="works_subjects")
     */
    private $subjects;

    public function __construct() {
        parent::__construct();
        $this->complete = false;
        $this->subjects = new ArrayCollection();
        $this->dates = new ArrayCollection();
        $this->contributions = new ArrayCollection();
        $this->checkedBy = new ArrayCollection();
    }

    /**
     * Return a string representation.
     *
     * @return string
     */
    public function __toString() : string {
        return $this->title;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Work
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set edition.
     *
     * @param int $edition
     *
     * @return Work
     */
    public function setEdition($edition) {
        $this->edition = $edition;

        return $this;
    }

    /**
     * Get edition.
     *
     * @return int
     */
    public function getEdition() {
        return $this->edition;
    }

    /**
     * Set volume.
     *
     * @param int $volume
     *
     * @return Work
     */
    public function setVolume($volume) {
        $this->volume = $volume;

        return $this;
    }

    /**
     * Get volume.
     *
     * @return int
     */
    public function getVolume() {
        return $this->volume;
    }

    /**
     * Set publicationPlace.
     *
     * @param string $publicationPlace
     *
     * @return Work
     */
    public function setPublicationPlace($publicationPlace) {
        $this->publicationPlace = $publicationPlace;

        return $this;
    }

    /**
     * Get publicationPlace.
     *
     * @return string
     */
    public function getPublicationPlace() {
        return $this->publicationPlace;
    }

    /**
     * Set physicalDescription.
     *
     * @param string $physicalDescription
     *
     * @return Work
     */
    public function setPhysicalDescription($physicalDescription) {
        $this->physicalDescription = $physicalDescription;

        return $this;
    }

    /**
     * Get physicalDescription.
     *
     * @return string
     */
    public function getPhysicalDescription() {
        return $this->physicalDescription;
    }

    /**
     * Set illustrations.
     *
     * @param bool $illustrations
     *
     * @return Work
     */
    public function setIllustrations($illustrations) {
        $this->illustrations = $illustrations;

        return $this;
    }

    /**
     * Get illustrations.
     *
     * @return bool
     */
    public function getIllustrations() {
        return $this->illustrations;
    }

    /**
     * Set frontispiece.
     *
     * @param bool $frontispiece
     *
     * @return Work
     */
    public function setFrontispiece($frontispiece) {
        $this->frontispiece = $frontispiece;

        return $this;
    }

    /**
     * Get frontispiece.
     *
     * @return bool
     */
    public function getFrontispiece() {
        return $this->frontispiece;
    }

    /**
     * Set translationDescription.
     *
     * @param string $translationDescription
     *
     * @return Work
     */
    public function setTranslationDescription($translationDescription) {
        $this->translationDescription = $translationDescription;

        return $this;
    }

    /**
     * Get translationDescription.
     *
     * @return string
     */
    public function getTranslationDescription() {
        return $this->translationDescription;
    }

    /**
     * Set dedication.
     *
     * @param string $dedication
     *
     * @return Work
     */
    public function setDedication($dedication) {
        $this->dedication = $dedication;

        return $this;
    }

    /**
     * Get dedication.
     *
     * @return string
     */
    public function getDedication() {
        return $this->dedication;
    }

    /**
     * Set worldcatUrl.
     *
     * @param string $worldcatUrl
     *
     * @return Work
     */
    public function setWorldcatUrl($worldcatUrl) {
        $this->worldcatUrl = $worldcatUrl;

        return $this;
    }

    /**
     * Get worldcatUrl.
     *
     * @return string
     */
    public function getWorldcatUrl() {
        return $this->worldcatUrl;
    }

    /**
     * Set transcription.
     *
     * @param bool $transcription
     *
     * @return Work
     */
    public function setTranscription($transcription) {
        $this->transcription = $transcription;

        return $this;
    }

    /**
     * Get transcription.
     *
     * @return bool
     */
    public function getTranscription() {
        return $this->transcription;
    }

    /**
     * Set physicalLocations.
     *
     * @param string $physicalLocations
     *
     * @return Work
     */
    public function setPhysicalLocations($physicalLocations) {
        $this->physicalLocations = $physicalLocations;

        return $this;
    }

    /**
     * Get physicalLocations.
     *
     * @return string
     */
    public function getPhysicalLocations() {
        return $this->physicalLocations;
    }

    /**
     * Set digitalLocations.
     *
     * @param string $digitalLocations
     *
     * @return Work
     */
    public function setDigitalLocations($digitalLocations) {
        $this->digitalLocations = $digitalLocations;

        return $this;
    }

    /**
     * Get digitalLocations.
     *
     * @return string
     */
    public function getDigitalLocations() {
        return $this->digitalLocations;
    }

    /**
     * Set digitalUrl.
     *
     * @param string $digitalUrl
     *
     * @return Work
     */
    public function setDigitalUrl($digitalUrl) {
        $this->digitalUrl = $digitalUrl;

        return $this;
    }

    /**
     * Get digitalUrl.
     *
     * @return string
     */
    public function getDigitalUrl() {
        return $this->digitalUrl;
    }

    /**
     * Set notes.
     *
     * @param string $notes
     *
     * @return Work
     */
    public function setNotes($notes) {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes.
     *
     * @return string
     */
    public function getNotes() {
        return $this->notes;
    }

    /**
     * Set genre.
     *
     * @return Work
     */
    public function setGenre(Genre $genre) {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre.
     *
     * @return Genre
     */
    public function getGenre() {
        return $this->genre;
    }

    /**
     * Set publisher.
     *
     * @return Work
     */
    public function setPublisher(Publisher $publisher) {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * Get publisher.
     *
     * @return Publisher
     */
    public function getPublisher() {
        return $this->publisher;
    }

    /**
     * Set workCategory.
     *
     * @return Work
     */
    public function setWorkCategory(WorkCategory $workCategory) {
        $this->workCategory = $workCategory;

        return $this;
    }

    /**
     * Get workCategory.
     *
     * @return WorkCategory
     */
    public function getWorkCategory() {
        return $this->workCategory;
    }

    /**
     * Add contribution.
     *
     * @return Work
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

    /**
     * Add date.
     *
     * @return Work
     */
    public function addDate(DateYear $date) {
        $this->dates[] = $date;

        return $this;
    }

    /**
     * Remove date.
     */
    public function removeDate(DateYear $date) : void {
        $this->dates->removeElement($date);
    }

    /**
     * Get dates.
     *
     * @return Collection
     */
    public function getDates() {
        return $this->dates;
    }

    /**
     * Set dates.
     *
     * @param Collection|DateYear[] $dates
     */
    public function setDates($dates) {
        $this->dates = $dates;

        return $this;
    }

    /**
     * Add subject.
     *
     * @return Work
     */
    public function addSubject(Subject $subject) {
        $this->subjects[] = $subject;

        return $this;
    }

    /**
     * Remove subject.
     */
    public function removeSubject(Subject $subject) : void {
        $this->subjects->removeElement($subject);
    }

    /**
     * Get subjects.
     *
     * @return Collection
     */
    public function getSubjects() {
        return $this->subjects;
    }

    public function setSubjects($subjects) {
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

    /**
     * Add checkedBy.
     *
     * @return Work
     */
    public function addCheckedBy(User $checkedBy) {
        if ( ! $this->checkedBy->contains($checkedBy)) {
            $this->checkedBy->add($checkedBy);
        }

        return $this;
    }

    /**
     * Remove checkedBy.
     */
    public function removeCheckedBy(User $checkedBy) : void {
        $this->checkedBy->removeElement($checkedBy);
    }

    /**
     * Get checkedBy.
     *
     * @return Collection
     */
    public function getCheckedBy() {
        return $this->checkedBy;
    }

    /**
     * @return Contribution
     */
    public function getFirstContribution() {
        foreach ($this->contributions as $contribution) {
            if ('aut' === $contribution->getRole()->getName()) {
                return $contribution;
            }
        }
        if (count($this->contributions) > 0) {
            return $this->contributions[0];
        }
    }

    /**
     * Set editorialNotes.
     *
     * @param string $editorialNotes
     *
     * @return Work
     */
    public function setEditorialNotes($editorialNotes) {
        $this->editorialNotes = $editorialNotes;

        return $this;
    }

    /**
     * Get editorialNotes.
     *
     * @return string
     */
    public function getEditorialNotes() {
        return $this->editorialNotes;
    }

    /**
     * Set complete.
     *
     * @param bool $complete
     *
     * @return Work
     */
    public function setComplete($complete) {
        $this->complete = $complete;

        return $this;
    }

    /**
     * Get complete.
     *
     * @return bool
     */
    public function getComplete() {
        return $this->complete;
    }
}
