<?php

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\DateYear;
use PHPUnit\Framework\TestCase;

class YearDateTest extends TestCase {

    /**
     * @dataProvider setValueData
     */
    public function testSetValue($expected, $value) {
        $date = new DateYear();
        $date->setValue($value);
        $this->assertEquals($expected, $date->getValue());
    }

    public function setValueData() {
        return array(
            ["1800", "1800"],
            ["c1800", "c1800"],
            ["c1800", "C1800"],
            ["c1800", " c 1800 "],

            ["1800-", "1800-"],
            ["1800-", "1800 - "],
            ["c1800-", "c1800-"],
            ["c1800-", "C1800-"],
            ["c1800-", " c 1800 - "],

            ["-1800", "-1800"],
            ["-1800", " - 1800"],
            ["-c1800", "-c1800"],
            ["-c1800", "-C1800"],
            ["-c1800", " - c1800"],

            ["1800-1801", "1800-1801"],
            ["c1800-1801", "c1800-1801"],
            ["1800-c1801", "1800-c1801"],
            ["c1800-c1801", "c1800-c1801"],
            ["c1800-c1801", "C1800-C1801"],

            ["1800-1801", " 1800 - 1801 "],
            ["c1800-1801", " c 1800 - 1801 "],
            ["1800-c1801", " 1800 - c 1801 "],
            ["c1800-c1801", " c 1800 - c 1801 "],
            ["c1800-c1801", " C 1800 - C 1801 "],
        );
    }

    /**
     * @dataProvider setBadValueData
     * @expectedException Exception
     */
    public function testSetBadValue($value) {
        $date = new DateYear();
        $date->setValue($value);
        $this->fail("Set value did not throw an exception.");
    }

    public function setBadValueData() {
        return array (
            [null],
            [false],
            [true],
            ['cheese'],
            ['180'],
            ['c180'],
            ['-180'],
            ['-19999'],
            [''],
            ['1990-1991-1992'],
            ['x1989'],
        );
    }

    /**
     * @dataProvider rangeData
     */
    public function testIsRange($expected, $value) {
        $date = new DateYear();
        $date->setValue($value);
        $this->assertEquals($expected, $date->isRange());
    }

    public function rangeData() {
        return array(
            [false, 1800],
            [false, "1800"],
            [false, "c1800"],
            [false, "C1799"],

            [true, -1800],
            [true, "-1800"],
            [true, "-c1800"],
            [true, "-C1800"],

            [true, "1800-"],
            [true, "c1800-"],
            [true, "C1800-"],

            [true, "1800-1805"],
            [true, "c1800-1805"],
            [true, "1800-c1805"],
            [true, "c1800-c1805"],
            [true, "1800-1805"],
            [true, "C1800-1805"],
            [true, "1800-C1805"],
            [true, "C1800-C1805"],
        );
    }

    /**
     * @dataProvider hasStartData
     */
    public function testHasStart($expected, $value) {
        $date = new DateYear();
        $date->setValue($value);
        $this->assertEquals($expected, $date->hasStart());
    }

    public function hasStartData() {
        return array(
            [true, 1800],
            [true, "1800"],
            [true, "c1800"],
            [true, "C1799"],

            [false, -1800],
            [false, "-1800"],
            [false, "-c1800"],
            [false, "-C1800"],

            [true, "1800-"],
            [true, "c1800-"],
            [true, "C1800-"],

            [true, "1800-1805"],
            [true, "c1800-1805"],
            [true, "1800-c1805"],
            [true, "c1800-c1805"],
            [true, "1800-1805"],
            [true, "C1800-1805"],
            [true, "1800-C1805"],
            [true, "C1800-C1805"],
        );
    }

    /**
     * @dataProvider getStartData
     */
    public function testGetStart($expected, $value) {
        $date = new DateYear();
        $date->setValue($value);
        $this->assertEquals($expected, $date->getStart());
    }

    public function getStartData() {
        return array(
            ["1800", 1800],
            ["1800", "1800"],
            ['c1800', "c1800"],
            ['c1799', "C1799"],

            [null, -1800],
            [null, "-1800"],
            [null, "-c1800"],
            [null, "-C1800"],

            ["1800", "1800-"],
            ["c1800", "c1800-"],
            ["c1800", "C1800-"],

            ["1800", "1800-1805"],
            ["c1800", "c1800-1805"],
            ["1800", "1800-c1805"],
            ["c1800", "c1800-c1805"],
            ["1800", "1800-1805"],
            ["c1800", "C1800-1805"],
            ["1800", "1800-C1805"],
            ["c1800", "C1800-C1805"],
        );
    }

    /**
     * @dataProvider hasEndData
     */
    public function testHasEnd($expected, $value) {
        $date = new DateYear();
        $date->setValue($value);
        $this->assertEquals($expected, $date->hasEnd());
    }

    public function hasEndData() {
        return array(
            [true, 1800],
            [true, "1800"],
            [true, "c1800"],
            [true, "C1799"],

            [true, -1800],
            [true, "-1800"],
            [true, "-c1800"],
            [true, "-C1800"],

            [false, "1800-"],
            [false, "c1800-"],
            [false, "C1800-"],

            [true, "1800-1805"],
            [true, "c1800-1805"],
            [true, "1800-c1805"],
            [true, "c1800-c1805"],
            [true, "1800-1805"],
            [true, "C1800-1805"],
            [true, "1800-C1805"],
            [true, "C1800-C1805"],
        );
    }

    /**
     * @dataProvider getEndData
     */
    public function testGetEnd($expected, $value) {
        $date = new DateYear();
        $date->setValue($value);
        $this->assertEquals($expected, $date->getEnd());
    }

    public function getEndData() {
        return array(
            ["1800", 1800],
            ["1800", "1800"],
            ['c1800', "c1800"],
            ['c1799', "C1799"],

            ["1800", -1800],
            ["1800", "-1800"],
            ["c1800", "-c1800"],
            ["c1800", "-C1800"],

            [null, "1800-"],
            [null, "c1800-"],
            [null, "C1800-"],

            ["1805", "1800-1805"],
            ["1805", "c1800-1805"],
            ["c1805", "1800-c1805"],
            ["c1805", "c1800-c1805"],
            ["1805", "1800-1805"],
            ["1805", "C1800-1805"],
            ["c1805", "1800-C1805"],
            ["c1805", "C1800-C1805"],
        );
    }

}
