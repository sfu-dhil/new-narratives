<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\WorkCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadWorkCategory form.
 */
class WorkCategoryFixtures extends Fixture implements FixtureGroupInterface {
    public static function getGroups() : array {
        return ['dev', 'test'];
    }

    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new WorkCategory();
            $fixture->setName('work_' . $i);
            $fixture->setLabel('Work ' . $i);
            $fixture->setDescription('work description ' . $i);
            $em->persist($fixture);
            $this->setReference('workcategory.' . $i, $fixture);
        }

        $em->flush();
    }
}
