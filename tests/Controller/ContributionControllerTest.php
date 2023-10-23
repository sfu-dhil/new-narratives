<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Nines\UserBundle\DataFixtures\UserFixtures;
use Nines\UtilBundle\TestCase\ControllerTestCase;

class ContributionControllerTest extends ControllerTestCase {
    public function testAnonIndex() : void {
        $crawler = $this->client->request('GET', '/contribution/');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
    }

    public function testUserIndex() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/contribution/');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminIndex() : void {
        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/contribution/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }
}
