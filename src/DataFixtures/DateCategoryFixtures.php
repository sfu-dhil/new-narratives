<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\DateCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadDateCategory form.
 */
class DateCategoryFixtures extends Fixture {
    /**
     * {@inheritdoc}
     */
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
