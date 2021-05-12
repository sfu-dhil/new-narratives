<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Work;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadWork form.
 */
class WorkFixtures extends Fixture implements DependentFixtureInterface {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new Work();
            $fixture->setTitle('Title ' . $i);
            $fixture->setEdition($i);
            $fixture->setVolume($i);
            $fixture->setPublicationPlace('PublicationPlace ' . $i);
            $fixture->setPhysicalDescription('PhysicalDescription ' . $i);
            $fixture->setIllustrations(0 === ($i % 2));
            $fixture->setFrontispiece(0 === ($i % 2));
            $fixture->setTranslationDescription('TranslationDescription ' . $i);
            $fixture->setDedication('Dedication ' . $i);
            $fixture->setWorldcatUrl('http://example.com/path/to' . $i);
            $fixture->setTranscription(0 === ($i % 2));
            $fixture->setPhysicalLocations('PhysicalLocations ' . $i);
            $fixture->setDigitalLocations('DigitalLocations ' . $i);
            $fixture->setDigitalUrl('http://example.com/path/to' . $i);
            $fixture->setNotes('Notes ' . $i);
            $fixture->setEditorialNotes('EditorialNotes ' . $i);
            $fixture->setComplete(0 === ($i % 2));
            $fixture->setGenre($this->getReference('genre.1'));
            $fixture->setPublisher($this->getReference('publisher.1'));
            $fixture->setWorkcategory($this->getReference('workcategory.1'));
            $fixture->addSubject($this->getReference('subject.1'));

            $em->persist($fixture);
            $this->setReference('work.' . $i, $fixture);
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
            GenreFixtures::class,
            PublisherFixtures::class,
            WorkCategoryFixtures::class,
            SubjectFixtures::class,
        ];
    }
}
