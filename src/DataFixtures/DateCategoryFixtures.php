<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\DateCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadDateCategory form.
 */
class DateCategoryFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new DateCategory();
            $fixture->setName('category_' . $i);
            $fixture->setLabel('Category ' . $i);
            $fixture->setDescription('category description ' . $i);

            $em->persist($fixture);
            $this->setReference('datecategory.' . $i, $fixture);
        }

        $em->flush();
    }
}
