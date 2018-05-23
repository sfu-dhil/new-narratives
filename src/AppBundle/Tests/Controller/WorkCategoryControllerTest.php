<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\WorkCategory;
use AppBundle\DataFixtures\ORM\LoadWorkCategory;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class WorkCategoryControllerTest extends BaseTestCase
{

    protected function getFixtures() {
        return [
            LoadUser::class,
            LoadWorkCategory::class
        ];
    }
    
    public function testAnonIndex() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/work_category/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('New')->filter('.btn')->count());
    }
    
    public function testUserIndex() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/work_category/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('New')->filter('.btn')->count());
    }
    
    public function testAdminIndex() {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/work_category/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->selectLink('New')->filter('.btn')->count());
    }
    
    public function testAnonShow() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/work_category/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('Edit')->count());
        $this->assertEquals(0, $crawler->selectLink('Delete')->count());
    }
    
    public function testUserShow() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/work_category/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('Edit')->count());
        $this->assertEquals(0, $crawler->selectLink('Delete')->count());
    }
    
    public function testAdminShow() {
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/work_category/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->selectLink('Edit')->count());
        $this->assertEquals(1, $crawler->selectLink('Delete')->count());
    }
    public function testAnonEdit() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/work_category/1/edit');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
    
    public function testUserEdit() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/work_category/1/edit');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
    
    public function testAdminEdit() {
        $client = $this->makeClient(LoadUser::ADMIN);
        $formCrawler = $client->request('GET', '/work_category/1/edit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
     
        $form = $formCrawler->selectButton('Update')->form([
            'work_category[name]' => 'cheese.',
            'work_category[label]' => 'cheese',
            'work_category[description]' => 'it is cheese'
        ]);
        
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('/work_category/1'));
        $responseCrawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $responseCrawler->filter('td:contains("cheese.")')->count());
    }
    
    public function testAnonNew() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/work_category/new');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
    
    public function testUserNew() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/work_category/new');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminNew() {
        $client = $this->makeClient(LoadUser::ADMIN);
        $formCrawler = $client->request('GET', '/work_category/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
           
        $form = $formCrawler->selectButton('Create')->form([
            'work_category[name]' => 'cheese.',
            'work_category[label]' => 'cheese',
            'work_category[description]' => 'it is cheese'
        ]);
        
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $responseCrawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $responseCrawler->filter('td:contains("cheese.")')->count());
    }
    
    public function testAnonDelete() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/work_category/1/delete');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
    
    public function testUserDelete() {
        $client = $this->makeClient(LoadUser::USER);
        $crawler = $client->request('GET', '/work_category/1/delete');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminDelete() {
        $preCount = count($this->em->getRepository(WorkCategory::class)->findAll());
        $client = $this->makeClient(LoadUser::ADMIN);
        $crawler = $client->request('GET', '/work_category/1/delete');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
        $responseCrawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $this->em->clear();
        $postCount = count($this->em->getRepository(WorkCategory::class)->findAll());
        $this->assertEquals($preCount - 1, $postCount);
    }

}
