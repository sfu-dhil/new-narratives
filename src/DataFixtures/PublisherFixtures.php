<?php

namespace App\DataFixtures;

use App\Entity\Publisher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadPublisher form.
 */
class PublisherFixtures extends Fixture
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
