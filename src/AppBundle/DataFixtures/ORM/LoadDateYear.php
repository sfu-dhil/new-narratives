<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\DateYear;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadDateYear form.
 */
class LoadDateYear extends Fixture implements DependentFixtureInterface {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new DateYear();
            $fixture->setValue(1900 + $i);
            $fixture->setDatecategory($this->getReference('dateCategory.1'));
            $fixture->setWork($this->getReference('work.1'));

            $em->persist($fixture);
            $this->setReference('dateyear.' . $i, $fixture);
        }

        $em->flush();
    }

    function getDependencies() {
        return [
            LoadDateCategory::class,
            LoadWork::class,
        ];
    }

}
