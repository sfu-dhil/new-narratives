<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Contribution;
use AppBundle\DataFixtures\ORM\LoadContribution;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class ContributionControllerTest extends BaseTestCase
{

    protected function getFixtures() {
        return [
            LoadUser::class,
            LoadContribution::class
        ];
    }
    
    public function testAnonIndex() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/contribution/');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
    
    public function testUserIndex() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/contribution/');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
    
    public function testAdminIndex() {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/contribution/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
}
