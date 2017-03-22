<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Work
 *
 * @ORM\Table(name="work")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WorkRepository")
 */
class Work extends AbstractEntity {

    /**
     * @var string
     * @ORM\Column(type="string", length=200)
     */
    private $title;

    /**
     * @var boolean
     * @ORM\Column(type="integer", nullable=true)
     */
    private $edition;

    /**
     * @var boolean
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
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $illustrations;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $frontispiece;

    /**
     * @var string
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $translationDescription;

    /**
     * @var string
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $dedication;

    /**
     * @var string
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $worldcatUrl;

    /**
     * @var boolean
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
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $digitalUrl;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    /**
     * @var Genre
     * @ORM\ManyToOne(targetEntity="Genre", inversedBy="works")
     * @ORM\JoinColumn(nullable=false)
     */
    private $genre;

    /**
     * @var Publisher
     * @ORM\ManyToOne(targetEntity="Publisher", inversedBy="works")
     * @ORM\JoinColumn(nullable=false)
     */
    private $publisher;

    /**
     * @var WorkCategory
     * @ORM\ManyToOne(targetEntity="WorkCategory", inversedBy="works")
     * @ORM\JoinColumn(nullable=false)
     */
    private $workCategory;

    /**
     * @var Collection|Contribution[]
     * @ORM\OneToMany(targetEntity="Contribution", mappedBy="work")
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
        $this->subjects = new ArrayCollection();
        $this->dates = new ArrayCollection();
        $this->contributions = new ArrayCollection();
    }

    /**
     * Return a string representation.
     * 
     * @return string
     */
    public function __toString() {
        return $this->title;
    }

    /**
     * Set title
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
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set edition
     *
     * @param integer $edition
     *
     * @return Work
     */
    public function setEdition($edition) {
        $this->edition = $edition;

        return $this;
    }

    /**
     * Get edition
     *
     * @return integer
     */
    public function getEdition() {
        return $this->edition;
    }

    /**
     * Set volume
     *
     * @param integer $volume
     *
     * @return Work
     */
    public function setVolume($volume) {
        $this->volume = $volume;

        return $this;
    }

    /**
     * Get volume
     *
     * @return integer
     */
    public function getVolume() {
        return $this->volume;
    }

    /**
     * Set publicationPlace
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
     * Get publicationPlace
     *
     * @return string
     */
    public function getPublicationPlace() {
        return $this->publicationPlace;
    }

    /**
     * Set physicalDescription
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
     * Get physicalDescription
     *
     * @return string
     */
    public function getPhysicalDescription() {
        return $this->physicalDescription;
    }

    /**
     * Set illustrations
     *
     * @param boolean $illustrations
     *
     * @return Work
     */
    public function setIllustrations($illustrations) {
        $this->illustrations = $illustrations;

        return $this;
    }

    /**
     * Get illustrations
     *
     * @return boolean
     */
    public function getIllustrations() {
        return $this->illustrations;
    }

    /**
     * Set frontispiece
     *
     * @param boolean $frontispiece
     *
     * @return Work
     */
    public function setFrontispiece($frontispiece) {
        $this->frontispiece = $frontispiece;

        return $this;
    }

    /**
     * Get frontispiece
     *
     * @return boolean
     */
    public function getFrontispiece() {
        return $this->frontispiece;
    }

    /**
     * Set translationDescription
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
     * Get translationDescription
     *
     * @return string
     */
    public function getTranslationDescription() {
        return $this->translationDescription;
    }

    /**
     * Set dedication
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
     * Get dedication
     *
     * @return string
     */
    public function getDedication() {
        return $this->dedication;
    }

    /**
     * Set worldcatUrl
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
     * Get worldcatUrl
     *
     * @return string
     */
    public function getWorldcatUrl() {
        return $this->worldcatUrl;
    }

    /**
     * Set transcription
     *
     * @param boolean $transcription
     *
     * @return Work
     */
    public function setTranscription($transcription) {
        $this->transcription = $transcription;

        return $this;
    }

    /**
     * Get transcription
     *
     * @return boolean
     */
    public function getTranscription() {
        return $this->transcription;
    }

    /**
     * Set physicalLocations
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
     * Get physicalLocations
     *
     * @return string
     */
    public function getPhysicalLocations() {
        return $this->physicalLocations;
    }

    /**
     * Set digitalLocations
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
     * Get digitalLocations
     *
     * @return string
     */
    public function getDigitalLocations() {
        return $this->digitalLocations;
    }

    /**
     * Set digitalUrl
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
     * Get digitalUrl
     *
     * @return string
     */
    public function getDigitalUrl() {
        return $this->digitalUrl;
    }

    /**
     * Set notes
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
     * Get notes
     *
     * @return string
     */
    public function getNotes() {
        return $this->notes;
    }

    /**
     * Set genre
     *
     * @param Genre $genre
     *
     * @return Work
     */
    public function setGenre(Genre $genre) {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return Genre
     */
    public function getGenre() {
        return $this->genre;
    }

    /**
     * Set publisher
     *
     * @param Publisher $publisher
     *
     * @return Work
     */
    public function setPublisher(Publisher $publisher) {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * Get publisher
     *
     * @return Publisher
     */
    public function getPublisher() {
        return $this->publisher;
    }

    /**
     * Set workCategory
     *
     * @param WorkCategory $workCategory
     *
     * @return Work
     */
    public function setWorkCategory(WorkCategory $workCategory) {
        $this->workCategory = $workCategory;

        return $this;
    }

    /**
     * Get workCategory
     *
     * @return WorkCategory
     */
    public function getWorkCategory() {
        return $this->workCategory;
    }

    /**
     * Add contribution
     *
     * @param Contribution $contribution
     *
     * @return Work
     */
    public function addContribution(Contribution $contribution) {
        $this->contributions[] = $contribution;

        return $this;
    }

    /**
     * Remove contribution
     *
     * @param Contribution $contribution
     */
    public function removeContribution(Contribution $contribution) {
        $this->contributions->removeElement($contribution);
    }

    /**
     * Get contributions
     *
     * @return Collection
     */
    public function getContributions() {
        return $this->contributions;
    }

    /**
     * Add date
     *
     * @param DateYear $date
     *
     * @return Work
     */
    public function addDate(DateYear $date) {
        $this->dates[] = $date;

        return $this;
    }

    /**
     * Remove date
     *
     * @param DateYear $date
     */
    public function removeDate(DateYear $date) {
        $this->dates->removeElement($date);
    }

    /**
     * Get dates
     *
     * @return Collection
     */
    public function getDates() {
        return $this->dates;
    }
    
    /**
     * Set dates
     * 
     * @param Collection|DateYear[] $dates
     */
    public function setDates($dates) {
        $this->dates = $dates;
        return $this;
    }

    /**
     * Add subject
     *
     * @param Subject $subject
     *
     * @return Work
     */
    public function addSubject(Subject $subject) {
        $this->subjects[] = $subject;

        return $this;
    }

    /**
     * Remove subject
     *
     * @param Subject $subject
     */
    public function removeSubject(Subject $subject) {
        $this->subjects->removeElement($subject);
    }

    /**
     * Get subjects
     *
     * @return Collection
     */
    public function getSubjects() {
        return $this->subjects;
    }

}
