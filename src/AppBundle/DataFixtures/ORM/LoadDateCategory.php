<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\DateCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadDateCategory form.
 */
class LoadDateCategory extends Fixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
            $fixture = new DateCategory();
            $fixture->setName('category_' . $i);
            $fixture->setLabel('Category ' . $i);
            $fixture->setDescription('category description ' . $i);
            
            $em->persist($fixture);
            $this->setReference('datecategory.' . $i, $fixture);
        }
        
        $em->flush();
        
    }
        
}
