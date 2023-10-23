<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\DateYear;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadDateYear form.
 */
class DateYearFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new DateYear();
            $fixture->setValue(1900 + $i);
            $fixture->setDatecategory($this->getReference('datecategory.1'));
            $fixture->setWork($this->getReference('work.1'));

            $em->persist($fixture);
            $this->setReference('dateyear.' . $i, $fixture);
        }

        $em->flush();
    }

    public function getDependencies() {
        return [
            DateCategoryFixtures::class,
            WorkFixtures::class,
        ];
    }
}
