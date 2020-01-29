<?php

namespace App\DataFixtures;

use App\Entity\DateYear;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadDateYear form.
 */
class DateYearFixtures extends Fixture implements DependentFixtureInterface {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) {
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

    function getDependencies() {
        return [
            DateCategoryFixtures::class,
            WorkFixtures::class,
        ];
    }

}
