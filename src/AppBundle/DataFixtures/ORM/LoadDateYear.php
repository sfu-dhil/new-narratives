<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\DateYear;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadDateYear form.
 */
class LoadDateYear extends Fixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
            $fixture = new DateYear();
            $fixture->setStart('Start ' . $i);
            $fixture->setStartCirca('StartCirca ' . $i);
            $fixture->setEnd('End ' . $i);
            $fixture->setEndCirca('EndCirca ' . $i);
            $fixture->setDatecategory($this->getReference('dateCategory.1'));
            $fixture->setWork($this->getReference('work.1'));
            
            $em->persist($fixture);
            $this->setReference('dateyear.' . $i, $fixture);
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
