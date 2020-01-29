<?php

namespace App\DataFixtures;

use App\Entity\WorkCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadWorkCategory form.
 */
class WorkCategoryFixtures extends Fixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
            $fixture = new WorkCategory();
            $fixture->setName('work_' . $i);
            $fixture->setLabel('Work ' . $i);
            $fixture->setDescription('work description ' . $i);
            $em->persist($fixture);
            $this->setReference('workcategory.' . $i, $fixture);
        }
        
        $em->flush();
        
    }    
        
}
