<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * LoadRole form.
 */
class RoleFixtures extends Fixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
            $fixture = new Role();
            $fixture->setName('role_' . $i);
            $fixture->setLabel('Role ' . $i);
            $fixture->setDescription('role description ' . $i);
            
            $em->persist($fixture);
            $this->setReference('role.' . $i, $fixture);
        }
        
        $em->flush();
        
    }
        
}
