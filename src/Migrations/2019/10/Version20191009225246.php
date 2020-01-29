<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191009225246 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE flag_entity DROP FOREIGN KEY FK_E37C49B9919FE4E5');
        $this->addSql('DROP TABLE flag');
        $this->addSql('DROP TABLE flag_entity');
    }

    public function down(Schema $schema) : void
    {
    }
}
