<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DateYearRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Nines\UtilBundle\Entity\AbstractEntity;

define('CIRCA_RE', '(c?)([1-9][0-9]{3})');
define('YEAR_RE', '/^' . CIRCA_RE . '$/');
define('RANGE_RE', '/^(?:' . CIRCA_RE . ')?-(?:' . CIRCA_RE . ')?$/');

#[ORM\Table(name: 'date_year')]
#[ORM\Entity(repositoryClass: DateYearRepository::class)]
class DateYear extends AbstractEntity {
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $start = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $startCirca;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $end = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $endCirca;

    #[ORM\ManyToOne(targetEntity: DateCategory::class, inversedBy: 'dates')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?DateCategory $dateCategory = null;

    #[ORM\ManyToOne(targetEntity: Work::class, inversedBy: 'dates')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Work $work = null;

    public function __construct() {
        parent::__construct();
        $this->start = null;
        $this->startCirca = false;
        $this->end = null;
        $this->endCirca = false;
    }

    public function __toString() : string {
        if (($this->startCirca === $this->endCirca) && ($this->start === $this->end)) {
            return ($this->startCirca ? 'c' : '') . $this->start;
        }

        return ($this->startCirca ? 'c' : '') . $this->start .
                '-' .
                ($this->endCirca ? 'c' : '') . $this->end;
    }

    public function getValue() : string {
        return $this->__toString();
    }

    public function setValue(null|int|string $value) : self {
        $value = mb_strtolower(preg_replace('/\s*/', '', (string) $value));
        $matches = [];
        if (false === mb_strpos($value, '-')) {
            // not a range
            if (preg_match(YEAR_RE, $value, $matches)) {
                $this->startCirca = ('c' === $matches[1]);
                $this->start = (int) $matches[2];
                $this->endCirca = $this->startCirca;
                $this->end = $this->start;
            } else {
                throw new Exception("Malformed date: '{$value}'");
            }

            return $this;
        }
        if ( ! preg_match(RANGE_RE, $value, $matches)) {
            throw new Exception("Malformed Date range '{$value}'");
        }

        $this->startCirca = ('c' === $matches[1]);
        $this->start = $matches[2] ? (int) $matches[2] : null;
        if (count($matches) > 3) {
            $this->endCirca = ('c' === $matches[3]);
            $this->end = $matches[4] ? (int) $matches[4] : null;
        }

        return $this;
    }

    public function isRange() : bool {
        return
            ($this->startCirca !== $this->endCirca)
            || ($this->start !== $this->end);
    }

    public function hasStart() : bool {
        return null !== $this->start && 0 !== $this->start;
    }

    public function getStart() : string {
        return ($this->startCirca ? 'c' : '') . $this->start;
    }

    public function hasEnd() : bool {
        return null !== $this->end && 0 !== $this->end;
    }

    public function getEnd() : string {
        return ($this->endCirca ? 'c' : '') . $this->end;
    }

    public function setDateCategory(DateCategory $dateCategory) : self {
        $this->dateCategory = $dateCategory;

        return $this;
    }

    public function getDateCategory() : ?DateCategory {
        return $this->dateCategory;
    }

    public function setWork(Work $work) : self {
        $this->work = $work;

        return $this;
    }

    public function getWork() : ?Work {
        return $this->work;
    }
}
