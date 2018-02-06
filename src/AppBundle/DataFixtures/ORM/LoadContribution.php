<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Contribution;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadContribution form.
 */
class LoadContribution extends Fixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
            $fixture = new Contribution();
            $fixture->setWork($this->getReference('work.1'));
            $fixture->setRole($this->getReference('role.1'));
            $fixture->setPerson($this->getReference('person.1'));
            
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
            
        ];
    }
    
        
}
