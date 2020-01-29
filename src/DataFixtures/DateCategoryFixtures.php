<?php

namespace App\DataFixtures;

use App\Entity\DateCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadDateCategory form.
 */
class DateCategoryFixtures extends Fixture
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
