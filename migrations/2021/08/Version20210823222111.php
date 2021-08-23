<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210823222111 extends AbstractMigration {
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE audio (id INT AUTO_INCREMENT NOT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', entity VARCHAR(128) NOT NULL, description LONGTEXT DEFAULT NULL, license LONGTEXT DEFAULT NULL, public TINYINT(1) NOT NULL, original_name VARCHAR(128) NOT NULL, path VARCHAR(128) NOT NULL, mime_type VARCHAR(64) NOT NULL, file_size INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pdf (id INT AUTO_INCREMENT NOT NULL, thumb_path VARCHAR(128) NOT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', entity VARCHAR(128) NOT NULL, description LONGTEXT DEFAULT NULL, license LONGTEXT DEFAULT NULL, public TINYINT(1) NOT NULL, original_name VARCHAR(128) NOT NULL, path VARCHAR(128) NOT NULL, mime_type VARCHAR(64) NOT NULL, file_size INT NOT NULL, FULLTEXT INDEX IDX_EF0DB8C545615306DE44026 (original_name, description), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE citation');
        $this->addSql('ALTER TABLE image CHANGE original_name original_name VARCHAR(128) NOT NULL, CHANGE entity entity VARCHAR(128) NOT NULL, CHANGE image_path path VARCHAR(128) NOT NULL, CHANGE image_size file_size INT NOT NULL');
    }

    public function down(Schema $schema) : void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE citation (id INT AUTO_INCREMENT NOT NULL, entity VARCHAR(128) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, citation LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_FABD9C7EE284468 (entity), FULLTEXT INDEX IDX_FABD9C7EFABD9C7E6DE44026 (citation, description), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE audio');
        $this->addSql('DROP TABLE pdf');
        $this->addSql('ALTER TABLE image CHANGE entity entity VARCHAR(80) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE original_name original_name VARCHAR(64) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE path image_path VARCHAR(128) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE file_size image_size INT NOT NULL');
    }
}
