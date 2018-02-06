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
            $fixture->setEdition('Edition ' . $i);
            $fixture->setVolume('Volume ' . $i);
            $fixture->setPublicationPlace('PublicationPlace ' . $i);
            $fixture->setPhysicalDescription('PhysicalDescription ' . $i);
            $fixture->setIllustrations('Illustrations ' . $i);
            $fixture->setFrontispiece('Frontispiece ' . $i);
            $fixture->setTranslationDescription('TranslationDescription ' . $i);
            $fixture->setDedication('Dedication ' . $i);
            $fixture->setWorldcatUrl('WorldcatUrl ' . $i);
            $fixture->setTranscription('Transcription ' . $i);
            $fixture->setPhysicalLocations('PhysicalLocations ' . $i);
            $fixture->setDigitalLocations('DigitalLocations ' . $i);
            $fixture->setDigitalUrl('DigitalUrl ' . $i);
            $fixture->setNotes('Notes ' . $i);
            $fixture->setEditorialNotes('EditorialNotes ' . $i);
            $fixture->setComplete('Complete ' . $i);
            $fixture->setGenre($this->getReference('genre.1'));
            $fixture->setPublisher($this->getReference('publisher.1'));
            $fixture->setWorkcategory($this->getReference('workCategory.1'));
            $fixture->setCheckedby($this->getReference('checkedBy.1'));
            $fixture->setSubjects($this->getReference('subjects.1'));
            
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
            
        ];
    }
    
        
}
