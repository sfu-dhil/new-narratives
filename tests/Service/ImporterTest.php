<?php

namespace App\Tests\Service;

use App\DataFixtures\ContributionFixtures;
use App\DataFixtures\DateCategoryFixtures;
use App\DataFixtures\PersonFixtures;
use App\DataFixtures\PublisherFixtures;
use App\DataFixtures\RoleFixtures;
use App\DataFixtures\SubjectFixtures;
use App\DataFixtures\WorkCategoryFixtures;
use App\DataFixtures\WorkFixtures;
use App\Entity\Work;
use App\Service\Importer;
use Doctrine\ORM\UnitOfWork;
use Nines\UserBundle\DataFixtures\UserFixtures;
use Nines\UtilBundle\Entity\AbstractEntity;
use Nines\UtilBundle\Tests\BaseCase;

class ImporterTest extends BaseCase {

    private ?Importer $importer = null;

    protected function fixtures() : array {
        return [
            UserFixtures::class,
            PersonFixtures::class,
            RoleFixtures::class,
            WorkFixtures::class,
            ContributionFixtures::class,
            DateCategoryFixtures::class,
            PublisherFixtures::class,
            SubjectFixtures::class,
            WorkCategoryFixtures::class,
        ];
    }

    public function testSetUp() {
        $this->assertInstanceOf(Importer::class, $this->importer);
    }

    protected function state($entity) : int {
        return $this->entityManager->getUnitOfWork()->getEntityState($entity);
    }

    public function testAddCheckedByNew() {
        $work = new Work();
        $this->importer->addCheckedBy($work, 'abc');
        $user = $work->getCheckedBy()[0];
        $this->assertNotNull($user);
        $this->assertEquals('abc@example.com', $user->getEmail());
        $this->assertEquals(UnitOfWork::STATE_NEW, $this->state($user));
    }

    public function testAddCheckedByExisting() {
        $work = new Work();
        $this->importer->addCheckedBy($work, 'Unprivileged user');
        $user = $work->getCheckedBy()[0];
        $this->assertNotNull($user);
        $this->assertEquals(UserFixtures::USER['username'], $user->getEmail());
        $this->assertEquals(UnitOfWork::STATE_MANAGED, $this->state($user));
    }

    public function testFindPersonNew() {
        $person = $this->importer->findPerson('Marge');
        $this->assertNotNull($person);
        $this->assertEquals('Marge', $person->getFullName());
        $this->assertEquals(UnitOfWork::STATE_NEW, $this->state($person));
    }

    public function testFindPersonExisting() {
        $person = $this->importer->findPerson('FullName 1');
        $this->assertNotNull($person);
        $this->assertEquals('FullName 1', $person->getFullName());
        $this->assertEquals(UnitOfWork::STATE_MANAGED, $this->state($person));
    }

    public function testFindRoleBad() {
        $this->expectExceptionMessageMatches("/Malformed role code/");
        $role = $this->importer->findRole('New Role');
    }

    public function testFindRoleUnknown() {
        $this->expectExceptionMessageMatches("/Unknown role code/");
        $role = $this->importer->findRole('[aaa]');
    }

    public function testFindRoleExisting() {
        $role = $this->importer->findRole('[role_a]');
        $this->assertNotNull($role);
        $this->assertEquals('Role 0', $role->getLabel());
        $this->assertEquals(UnitOfWork::STATE_MANAGED, $this->state($role));
    }

    public function testAddNullContribution() {
        /** @var Work $work */
        $work = $this->getReference('work.1');
        $count = count($work->getContributions());
        $this->importer->addContribution($work, '', '[aut]');
        $this->assertEquals($count, count($work->getContributions()));
        $this->importer->addContribution($work, 'Felicia', '');
        $this->assertEquals($count, count($work->getContributions()));
    }

    public function testAddContribution() {
        /** @var Work $work */
        $work = $this->getReference('work.1');
        $count = count($work->getContributions());
        $this->importer->addContribution($work, 'Felicia', '[role_a]');
        $this->assertEquals($count+1, count($work->getContributions()));
        $con = $work->getContributions()[1];
        $this->assertNotNull($con);

        $this->assertEquals('Felicia', $con->getPerson()->getFullName());
        $this->assertEquals('Role 0', $con->getRole()->getLabel());
        $this->assertEquals($work, $con->getWork());
    }

    public function testAddDateUnknownCategory() {
        $this->expectExceptionMessageMatches('/Cannot find date category/');
        /** @var Work $work */
        $work = $this->getReference('work.1');
        $this->importer->addDate($work, '1900', 'abc');
    }

    public function testAddDateMalformed() {
        $this->expectExceptionMessageMatches('/Malformed date/');
        /** @var Work $work */
        $work = $this->getReference('work.1');
        $this->importer->addDate($work, '1900-01', 'Category 0');
    }

    public function testAddDate() {
        /** @var Work $work */
        $work = $this->getReference('work.1');
        $this->importer->addDate($work, '1900', 'Category 0');
        $date = $work->getDates()[0];
        $this->assertNotNull($date);
        $this->assertEquals(UnitOfWork::STATE_NEW, $this->state($date));
        $this->assertEquals('Category 0', $date->getDateCategory()->getLabel());
        $this->assertEquals('1900', $date->getStart());
    }


    public function testFindPublisherNew() {
        $publisher = $this->importer->findPublisher('Marge');
        $this->assertNotNull($publisher);
        $this->assertEquals('Marge', $publisher->getName());
        $this->assertEquals(UnitOfWork::STATE_NEW, $this->state($publisher));
    }

    public function testFindPublisherExisting() {
        $publisher = $this->importer->findPublisher('Name 1');
        $this->assertNotNull($publisher);
        $this->assertEquals('Name 1', $publisher->getName());
        $this->assertEquals(UnitOfWork::STATE_MANAGED, $this->state($publisher));
    }

    /**
     * @dataProvider yesNoData
     */
    public function testYesNo($expected, $value, $exception = false) {
        if($exception) {
            $this->expectExceptionMessageMatches('|Malformed Yes/No field|');
        }
        $this->assertEquals($expected, $this->importer->yesNo($value));
    }

    public function yesNoData() {
        return [
            [true, 'yes'],
            [true, 'Yes'],
            [true, 'YES'],
            [true, 'Y'],
            [true, 'y'],

            [false, 'no'],
            [false, 'No'],
            [false, 'NO'],
            [false, 'N'],
            [false, 'n'],

            [null, ''],
            [null, 'abc', true],
        ];
    }

    public function testAddSubjectUnknown() {
        $this->expectExceptionMessageMatches('/Unknown subject/');
        /** @var Work $work */
        $work = $this->getReference('work.0');
        $this->importer->addSubjects($work, 'unknown');
    }

    public function testSubjectBlank() {
        /** @var Work $work */
        $work = $this->getReference('work.0');
        $this->importer->addSubjects($work, '');
        $this->assertCount(1, $work->getSubjects());
    }

    public function testAddSubject() {
        /** @var Work $work */
        $work = $this->getReference('work.0');
        $this->importer->addSubjects($work, 'Subject 0');
        $this->assertCount(2, $work->getSubjects());
    }

    public function testSetGenreUnknown() {
        $this->expectExceptionMessageMatches('/Unknown genre/');
        /** @var Work $work */
        $work = $this->getReference('work.0');
        $this->importer->setGenre($work, 'abc');
    }

    public function testSetGenre() {
        /** @var Work $work */
        $work = $this->getReference('work.0');
        $this->importer->setGenre($work, 'Genre 0');
        $this->assertEquals('genre_0', $work->getGenre()->getName());
    }

    /**
     * @dataProvider getUrlsData
     */
    public function testGetUrls($expected, $data) {
        $urls = $this->importer->getUrls($data);
        $this->assertSame($expected, $urls);
    }

    public function getUrlsData() {
        return [
            [[], 'abc123'],
            [['http://example.com'], 'also here: http://example.com and there.'],
            [['https://x.com', 'https://y.com/with/path'], 'also here: https://x.com and https://y.com/with/path'],
        ];
    }

    public function testSetWorkCategoryBlank() {
        /** @var Work $work */
        $work = $this->getReference('work.0');
        $category = $work->getWorkCategory();
        $this->importer->setWorkCategory($work, '');
        $this->assertEquals($category, $work->getWorkCategory());
    }

    public function testSetWorkCategoryUnknown() {
        $this->expectExceptionMessageMatches('/Unknown work category/');
        /** @var Work $work */
        $work = $this->getReference('work.0');
        $this->importer->setWorkCategory($work, 'abc123');
    }

    public function testSetWorkCategory() {
        $this->expectExceptionMessageMatches('/Unknown work category/');
        /** @var Work $work */
        $work = $this->getReference('work.0');
        $this->importer->setWorkCategory($work, 'Category 2');
        $this->assertEquals('category_2', $work->getWorkCategory()->getName());
    }

    protected function setUp() : void {
        parent::setUp();
        $this->importer = self::$container->get(Importer::class);
    }

}
