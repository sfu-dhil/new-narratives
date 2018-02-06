<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Contribution;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadContribution form.
 */
class LoadContribution extends Fixture implements DependentFixtureInterface {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Contribution();
            $fixture->setWork($this->getReference('work.' . $i));
            $fixture->setRole($this->getReference('role.' . $i));
            $fixture->setPerson($this->getReference('person.' . $i));

            $em->persist($fixture);
            $this->setReference('contribution.' . $i, $fixture);
        }

        $em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        // add dependencies here, or remove this 
        // function and "implements DependentFixtureInterface" above
        return [
            LoadWork::class,
            LoadRole::class,
            LoadPerson::class,
        ];
    }

}
