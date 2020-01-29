<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace AppBundle\Tests\Controller;

use AppBundle\DataFixtures\ORM\LoadWork;
use AppBundle\Entity\Work;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class WorkControllerTest extends BaseTestCase {
    protected function getFixtures() {
        return [
            LoadUser::class,
            LoadWork::class,
        ];
    }

    public function testAnonIndex() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/work/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('New')->filter('.btn')->count());
    }

    public function testUserIndex() : void {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/work/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('New')->filter('.btn')->count());
    }

    public function testAdminIndex() : void {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/work/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->selectLink('New')->filter('.btn')->count());
    }

    public function testAnonShow() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/work/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    public function testUserShow() : void {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/work/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    public function testAdminShow() : void {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/work/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(3, $crawler->selectLink('Edit')->count());
        $this->assertSame(1, $crawler->selectLink('Delete')->count());
    }

    public function testAnonEdit() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/work/1/edit');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testUserEdit() : void {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/work/1/edit');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminEdit() : void {
        $client = $this->makeClient(LoadUser::ADMIN);
        $formCrawler = $client->request('GET', '/work/1/edit');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Update')->form([
            'work[title]' => 'cheese.',
            'work[workCategory]' => 1,
            'work[edition]' => 1,
            'work[volume]' => 1,
            'work[publicationPlace]' => 'London',
            'work[publisher]' => 1,
            'work[physicalDescription]' => 'looks like cheese',
            'work[illustrations]' => 1,
            'work[frontispiece]' => 1,
            'work[translationDescription]' => 'translated cheese',
            'work[dedication]' => 'to cheese',
            'work[worldcatUrl]' => 'https://www.worldcat.org',
            'work[subjects]' => 1,
            'work[genre]' => 1,
            'work[transcription]' => 1,
            'work[physicalLocations]' => 'London',
            'work[digitalLocations]' => 'SFU',
            'work[digitalUrl]' => 'http://library.sfu.ca',
            'work[notes]' => 'it is cheese',
        ]);

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('/work/1'));
        $responseCrawler = $client->followRedirect();
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("cheese.")')->count());
    }

    public function testAnonNew() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/work/new');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testUserNew() : void {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/work/new');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminNew() : void {
        $client = $this->makeClient(LoadUser::ADMIN);
        $formCrawler = $client->request('GET', '/work/new');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Create')->form([
            'work[title]' => 'cheese.',
            'work[workCategory]' => 1,
            'work[edition]' => 1,
            'work[volume]' => 1,
            'work[publicationPlace]' => 'London',
            'work[publisher]' => 1,
            'work[physicalDescription]' => 'looks like cheese',
            'work[illustrations]' => 1,
            'work[frontispiece]' => 1,
            'work[translationDescription]' => 'translated cheese',
            'work[dedication]' => 'to cheese',
            'work[worldcatUrl]' => 'https://www.worldcat.org',
            'work[subjects]' => 1,
            'work[genre]' => 1,
            'work[transcription]' => 1,
            'work[physicalLocations]' => 'London',
            'work[digitalLocations]' => 'SFU',
            'work[digitalUrl]' => 'http://library.sfu.ca',
            'work[notes]' => 'it is cheese',
        ]);

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $responseCrawler = $client->followRedirect();
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("cheese.")')->count());
    }

    public function testAnonDelete() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/work/1/delete');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testUserDelete() : void {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/work/1/delete');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminDelete() : void {
        $preCount = count($this->em->getRepository(Work::class)->findAll());
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/work/1/delete');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
        $responseCrawler = $client->followRedirect();
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $this->em->clear();
        $postCount = count($this->em->getRepository(Work::class)->findAll());
        $this->assertSame($preCount - 1, $postCount);
    }
}
