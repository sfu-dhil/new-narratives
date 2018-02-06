<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Publisher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadPublisher form.
 */
class LoadPublisher extends Fixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
            $fixture = new Publisher();
            $fixture->setName('Name ' . $i);
            
            $em->persist($fixture);
            $this->setReference('publisher.' . $i, $fixture);
        }
        
        $em->flush();
        
    }
    
}
