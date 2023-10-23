<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Work;
use Nines\UserBundle\DataFixtures\UserFixtures;
use Nines\UtilBundle\TestCase\ControllerTestCase;

class WorkControllerTest extends ControllerTestCase {
    public function testAnonIndex() : void {
        $crawler = $this->client->request('GET', '/work/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('New')->filter('.btn')->count());
    }

    public function testUserIndex() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/work/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('New')->filter('.btn')->count());
    }

    public function testAdminIndex() : void {
        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/work/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->selectLink('New')->filter('.btn')->count());
    }

    public function testAnonShow() : void {
        $crawler = $this->client->request('GET', '/work/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    public function testUserShow() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/work/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    public function testAdminShow() : void {
        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/work/1');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(3, $crawler->selectLink('Edit')->count());
        $this->assertSame(1, $crawler->selectLink('Delete')->count());
    }

    public function testAnonEdit() : void {
        $crawler = $this->client->request('GET', '/work/1/edit');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
    }

    public function testUserEdit() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/work/1/edit');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminEdit() : void {
        $this->login(UserFixtures::ADMIN);
        $formCrawler = $this->client->request('GET', '/work/1/edit');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Update')->form([
            'work[title]' => 'cheese.',
            'work[workCategory]' => '1',
            'work[edition]' => '1',
            'work[volume]' => '1',
            'work[publicationPlace]' => 'London',
            'work[publisher]' => '1',
            'work[physicalDescription]' => 'looks like cheese',
            'work[illustrations]' => '1',
            'work[frontispiece]' => '1',
            'work[translationDescription]' => 'translated cheese',
            'work[dedication]' => 'to cheese',
            'work[worldcatUrl]' => 'https://www.worldcat.org',
            'work[subjects]' => '1',
            'work[genre]' => '1',
            'work[transcription]' => '1',
            'work[physicalLocations]' => 'London',
            'work[digitalLocations]' => 'SFU',
            'work[digitalUrl]' => 'http://library.sfu.ca',
            'work[notes]' => 'it is cheese',
        ]);

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/work/1'));
        $responseCrawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("cheese.")')->count());
    }

    public function testAnonNew() : void {
        $crawler = $this->client->request('GET', '/work/new');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
    }

    public function testUserNew() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/work/new');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminNew() : void {
        $this->login(UserFixtures::ADMIN);
        $formCrawler = $this->client->request('GET', '/work/new');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Create')->form([
            'work[title]' => 'cheese.',
            'work[workCategory]' => '1',
            'work[edition]' => '1',
            'work[volume]' => '1',
            'work[publicationPlace]' => 'London',
            'work[publisher]' => '1',
            'work[physicalDescription]' => 'looks like cheese',
            'work[illustrations]' => '1',
            'work[frontispiece]' => '1',
            'work[translationDescription]' => 'translated cheese',
            'work[dedication]' => 'to cheese',
            'work[worldcatUrl]' => 'https://www.worldcat.org',
            'work[subjects]' => '1',
            'work[genre]' => '1',
            'work[transcription]' => '1',
            'work[physicalLocations]' => 'London',
            'work[digitalLocations]' => 'SFU',
            'work[digitalUrl]' => 'http://library.sfu.ca',
            'work[notes]' => 'it is cheese',
        ]);

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $responseCrawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("cheese.")')->count());
    }

    public function testAnonDelete() : void {
        $crawler = $this->client->request('GET', '/work/1/delete');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
    }

    public function testUserDelete() : void {
        $this->login(UserFixtures::USER);
        $crawler = $this->client->request('GET', '/work/1/delete');
        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdminDelete() : void {
        $preCount = count($this->em->getRepository(Work::class)->findAll());
        $this->login(UserFixtures::ADMIN);
        $crawler = $this->client->request('GET', '/work/1/delete');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $responseCrawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->em->clear();
        $postCount = count($this->em->getRepository(Work::class)->findAll());
        $this->assertSame($preCount - 1, $postCount);
    }
}
