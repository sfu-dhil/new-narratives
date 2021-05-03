<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\WorkCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadWorkCategory form.
 */
class WorkCategoryFixtures extends Fixture {
    /**
     * {@inheritdoc}
     */
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
