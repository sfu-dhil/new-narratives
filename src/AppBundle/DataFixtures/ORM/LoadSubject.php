<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Subject;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadSubject form.
 */
class LoadSubject extends Fixture implements DependentFixtureInterface {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Subject();
            $fixture->setName('subject_' . $i);
            $fixture->setLabel('Subject ' . $i);
            $fixture->setDescription('Subject description ' . $i);
            $fixture->setSubjectsource($this->getReference('subjectsource.1'));
            $fixture->setWorks($this->getReference('works.1'));

            $em->persist($fixture);
            $this->setReference('subject.' . $i, $fixture);
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
            LoadSubjectSource::class,
        ];
    }

}
