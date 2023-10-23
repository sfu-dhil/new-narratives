<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\DateYear;
use Exception;
use PHPUnit\Framework\TestCase;

class YearDateTest extends TestCase {
    /**
     * @dataProvider setValueData
     */
    public function testSetValue(mixed $expected, mixed $value) : void {
        $date = new DateYear();
        $date->setValue($value);
        $this->assertSame($expected, $date->getValue());
    }

    public function setValueData() {
        return [
            ['1800', '1800'],
            ['c1800', 'c1800'],
            ['c1800', 'C1800'],
            ['c1800', ' c 1800 '],

            ['1800-', '1800-'],
            ['1800-', '1800 - '],
            ['c1800-', 'c1800-'],
            ['c1800-', 'C1800-'],
            ['c1800-', ' c 1800 - '],

            ['-1800', '-1800'],
            ['-1800', ' - 1800'],
            ['-c1800', '-c1800'],
            ['-c1800', '-C1800'],
            ['-c1800', ' - c1800'],

            ['1800-1801', '1800-1801'],
            ['c1800-1801', 'c1800-1801'],
            ['1800-c1801', '1800-c1801'],
            ['c1800-c1801', 'c1800-c1801'],
            ['c1800-c1801', 'C1800-C1801'],

            ['1800-1801', ' 1800 - 1801 '],
            ['c1800-1801', ' c 1800 - 1801 '],
            ['1800-c1801', ' 1800 - c 1801 '],
            ['c1800-c1801', ' c 1800 - c 1801 '],
            ['c1800-c1801', ' C 1800 - C 1801 '],
        ];
    }

    /**
     * @dataProvider setBadValueData
     */
    public function testSetBadValue(mixed $value) : void {
        $this->expectException(Exception::class);

        $date = new DateYear();
        $date->setValue($value);
        $this->fail('Set value did not throw an exception.');
    }

    public function setBadValueData() {
        return [
            [null],
            ['cheese'],
            ['180'],
            ['c180'],
            ['-180'],
            ['-19999'],
            [''],
            ['1990-1991-1992'],
            ['x1989'],
        ];
    }

    /**
     * @dataProvider rangeData
     */
    public function testIsRange(mixed $expected, mixed $value) : void {
        $date = new DateYear();
        $date->setValue($value);
        $this->assertSame($expected, $date->isRange());
    }

    public function rangeData() {
        return [
            [false, 1800],
            [false, '1800'],
            [false, 'c1800'],
            [false, 'C1799'],

            [true, -1800],
            [true, '-1800'],
            [true, '-c1800'],
            [true, '-C1800'],

            [true, '1800-'],
            [true, 'c1800-'],
            [true, 'C1800-'],

            [true, '1800-1805'],
            [true, 'c1800-1805'],
            [true, '1800-c1805'],
            [true, 'c1800-c1805'],
            [true, '1800-1805'],
            [true, 'C1800-1805'],
            [true, '1800-C1805'],
            [true, 'C1800-C1805'],
        ];
    }

    /**
     * @dataProvider hasStartData
     */
    public function testHasStart(mixed $expected, mixed $value) : void {
        $date = new DateYear();
        $date->setValue($value);
        $this->assertSame($expected, $date->hasStart());
    }

    public function hasStartData() {
        return [
            [true, 1800],
            [true, '1800'],
            [true, 'c1800'],
            [true, 'C1799'],

            [false, -1800],
            [false, '-1800'],
            [false, '-c1800'],
            [false, '-C1800'],

            [true, '1800-'],
            [true, 'c1800-'],
            [true, 'C1800-'],

            [true, '1800-1805'],
            [true, 'c1800-1805'],
            [true, '1800-c1805'],
            [true, 'c1800-c1805'],
            [true, '1800-1805'],
            [true, 'C1800-1805'],
            [true, '1800-C1805'],
            [true, 'C1800-C1805'],
        ];
    }

    /**
     * @dataProvider getStartData
     */
    public function testGetStart(mixed $expected, mixed $value) : void {
        $date = new DateYear();
        $date->setValue($value);
        $this->assertSame($expected, $date->getStart());
    }

    public function getStartData() {
        return [
            ['1800', 1800],
            ['1800', '1800'],
            ['c1800', 'c1800'],
            ['c1799', 'C1799'],

            ['', -1800],
            ['', '-1800'],
            ['', '-c1800'],
            ['', '-C1800'],

            ['1800', '1800-'],
            ['c1800', 'c1800-'],
            ['c1800', 'C1800-'],

            ['1800', '1800-1805'],
            ['c1800', 'c1800-1805'],
            ['1800', '1800-c1805'],
            ['c1800', 'c1800-c1805'],
            ['1800', '1800-1805'],
            ['c1800', 'C1800-1805'],
            ['1800', '1800-C1805'],
            ['c1800', 'C1800-C1805'],
        ];
    }

    /**
     * @dataProvider hasEndData
     */
    public function testHasEnd(mixed $expected, mixed $value) : void {
        $date = new DateYear();
        $date->setValue($value);
        $this->assertSame($expected, $date->hasEnd());
    }

    public function hasEndData() {
        return [
            [true, 1800],
            [true, '1800'],
            [true, 'c1800'],
            [true, 'C1799'],

            [true, -1800],
            [true, '-1800'],
            [true, '-c1800'],
            [true, '-C1800'],

            [false, '1800-'],
            [false, 'c1800-'],
            [false, 'C1800-'],

            [true, '1800-1805'],
            [true, 'c1800-1805'],
            [true, '1800-c1805'],
            [true, 'c1800-c1805'],
            [true, '1800-1805'],
            [true, 'C1800-1805'],
            [true, '1800-C1805'],
            [true, 'C1800-C1805'],
        ];
    }

    /**
     * @dataProvider getEndData
     */
    public function testGetEnd(mixed $expected, mixed $value) : void {
        $date = new DateYear();
        $date->setValue($value);
        $this->assertSame($expected, $date->getEnd());
    }

    public function getEndData() {
        return [
            ['1800', 1800],
            ['1800', '1800'],
            ['c1800', 'c1800'],
            ['c1799', 'C1799'],

            ['1800', -1800],
            ['1800', '-1800'],
            ['c1800', '-c1800'],
            ['c1800', '-C1800'],

            ['', '1800-'],
            ['', 'c1800-'],
            ['', 'C1800-'],

            ['1805', '1800-1805'],
            ['1805', 'c1800-1805'],
            ['c1805', '1800-c1805'],
            ['c1805', 'c1800-c1805'],
            ['1805', '1800-1805'],
            ['1805', 'C1800-1805'],
            ['c1805', '1800-C1805'],
            ['c1805', 'C1800-C1805'],
        ];
    }
}
