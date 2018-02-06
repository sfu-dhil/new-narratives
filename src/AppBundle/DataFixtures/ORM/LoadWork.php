<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Work;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadWork form.
 */
class LoadWork extends Fixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
            $fixture = new Work();
            $fixture->setTitle('Title ' . $i);
            $fixture->setEdition($i);
            $fixture->setVolume($i);
            $fixture->setPublicationPlace('PublicationPlace ' . $i);
            $fixture->setPhysicalDescription('PhysicalDescription ' . $i);
            $fixture->setIllustrations(($i % 2) === 0);
            $fixture->setFrontispiece(($i % 2) === 0);
            $fixture->setTranslationDescription('TranslationDescription ' . $i);
            $fixture->setDedication('Dedication ' . $i);
            $fixture->setWorldcatUrl('http://example.com/path/to' . $i);
            $fixture->setTranscription(($i % 2) === 0);
            $fixture->setPhysicalLocations('PhysicalLocations ' . $i);
            $fixture->setDigitalLocations('DigitalLocations ' . $i);
            $fixture->setDigitalUrl('http://example.com/path/to' . $i);
            $fixture->setNotes('Notes ' . $i);
            $fixture->setEditorialNotes('EditorialNotes ' . $i);
            $fixture->setComplete(($i % 2) === 0);
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
            LoadGenre::class,
            LoadPublisher::class,
            LoadWorkCategory::class,
            LoadSubject::class,
        ];
    }
    
        
}
