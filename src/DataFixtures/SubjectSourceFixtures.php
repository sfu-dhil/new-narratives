<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\SubjectSource;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadSubjectSource form.
 */
class SubjectSourceFixtures extends Fixture {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new SubjectSource();
            $fixture->setName('source_' . $i);
            $fixture->setLabel('Source ' . $i);
            $fixture->setDescription('source description ' . $i);

            $em->persist($fixture);
            $this->setReference('subjectsource.' . $i, $fixture);
        }

        $em->flush();
    }
}
