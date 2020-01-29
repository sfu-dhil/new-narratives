<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace AppBundle\Tests\Controller;

use AppBundle\DataFixtures\ORM\LoadDateCategory;
use AppBundle\Entity\DateCategory;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class DateCategoryControllerTest extends BaseTestCase {
    protected function getFixtures() {
        return [
            LoadUser::class,
            LoadDateCategory::class,
        ];
    }

    public function testAnonIndex() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/date_category/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('New')->filter('.btn')->count());
    }

    public function testUserIndex() : void {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/date_category/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('New')->filter('.btn')->count());
    }

    public function testAdminIndex() : void {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/date_category/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->selectLink('New')->filter('.btn')->count());
    }

    public function testAnonShow() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/date_category/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    public function testUserShow() : void {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/date_category/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->selectLink('Edit')->count());
        $this->assertSame(0, $crawler->selectLink('Delete')->count());
    }

    public function testAdminShow() : void {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/date_category/1');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->selectLink('Edit')->count());
        $this->assertSame(1, $crawler->selectLink('Delete')->count());
    }

    public function testAnonEdit() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/date_category/1/edit');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testUserEdit() : void {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/date_category/1/edit');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminEdit() : void {
        $client = $this->makeClient(LoadUser::ADMIN);
        $formCrawler = $client->request('GET', '/date_category/1/edit');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Update')->form([
            'date_category[name]' => 'cheese.',
            'date_category[label]' => 'cheese label',
            'date_category[description]' => 'this is a good cheese',
        ]);

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('/date_category/1'));
        $responseCrawler = $client->followRedirect();
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("cheese.")')->count());
    }

    public function testAnonNew() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/date_category/new');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testUserNew() : void {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/date_category/new');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminNew() : void {
        $client = $this->makeClient(LoadUser::ADMIN);
        $formCrawler = $client->request('GET', '/date_category/new');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Create')->form([
            'date_category[name]' => 'cheese.',
            'date_category[label]' => 'cheese label',
            'date_category[description]' => 'this is a good cheese',
        ]);

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $responseCrawler = $client->followRedirect();
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $responseCrawler->filter('td:contains("cheese.")')->count());
    }

    public function testAnonDelete() : void {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/date_category/1/delete');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testUserDelete() : void {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/date_category/1/delete');
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminDelete() : void {
        $preCount = count($this->em->getRepository(DateCategory::class)->findAll());
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/date_category/1/delete');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
        $responseCrawler = $client->followRedirect();
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $this->em->clear();
        $postCount = count($this->em->getRepository(DateCategory::class)->findAll());
        $this->assertSame($preCount - 1, $postCount);
    }
}
