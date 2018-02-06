<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Work;
use AppBundle\DataFixtures\ORM\LoadWork;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class WorkControllerTest extends BaseTestCase
{

    protected function getFixtures() {
        return [
            LoadUser::class,
            LoadWork::class,
        ];
    }
    
    public function testAnonIndex() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/work/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('New')->count());
    }
    
    public function testUserIndex() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/work/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('New')->count());
    }
    
    public function testAdminIndex() {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/work/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->selectLink('New')->count());
    }
    
    public function testAnonShow() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/work/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('Edit')->count());
        $this->assertEquals(0, $crawler->selectLink('Delete')->count());
    }
    
    public function testUserShow() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/work/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('Edit')->count());
        $this->assertEquals(0, $crawler->selectLink('Delete')->count());
    }
    
    public function testAdminShow() {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/work/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(3, $crawler->selectLink('Edit')->count());
        $this->assertEquals(1, $crawler->selectLink('Delete')->count());
    }
    public function testAnonEdit() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/work/1/edit');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
    
    public function testUserEdit() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/work/1/edit');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
    
    public function testAdminEdit() {
        $client = $this->makeClient(LoadUser::ADMIN);
        $formCrawler = $client->request('GET', '/work/1/edit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );        
        $form = $formCrawler->selectButton('Update')->form([
            // DO STUFF HERE.
            // 'works[FIELDNAME]' => 'FIELDVALUE',
        ]);
        
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('/work/1'));
        $responseCrawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // $this->assertEquals(1, $responseCrawler->filter('td:contains("FIELDVALUE")')->count());
    }
    
    public function testAnonNew() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/work/new');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
    
    public function testUserNew() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/work/new');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminNew() {
        $client = $this->makeClient(LoadUser::ADMIN);
        $formCrawler = $client->request('GET', '/work/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );        
        $form = $formCrawler->selectButton('Create')->form([
            // DO STUFF HERE.
            // 'works[FIELDNAME]' => 'FIELDVALUE',
        ]);
        
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $responseCrawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // $this->assertEquals(1, $responseCrawler->filter('td:contains("FIELDVALUE")')->count());
    }
    
    public function testAnonDelete() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/work/1/delete');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
    
    public function testUserDelete() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/work/1/delete');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminDelete() {
        $preCount = count($this->em->getRepository(Work::class)->findAll());
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/work/1/delete');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
        $responseCrawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $this->em->clear();
        $postCount = count($this->em->getRepository(Work::class)->findAll());
        $this->assertEquals($preCount - 1, $postCount);
    }

}
