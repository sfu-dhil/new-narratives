<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\SubjectSource;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadSubjectSource form.
 */
class LoadSubjectSource extends Fixture 
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
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
