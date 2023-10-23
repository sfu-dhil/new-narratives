<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231025232434 extends AbstractMigration {
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE date_category CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE genre CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE nines_blog_page DROP FOREIGN KEY FK_F4DA3AB0A76ED395');
        $this->addSql('ALTER TABLE nines_blog_page ADD CONSTRAINT FK_23FD24C7A76ED395 FOREIGN KEY (user_id) REFERENCES nines_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nines_blog_page RENAME INDEX idx_f4da3ab0a76ed395 TO IDX_23FD24C7A76ED395');
        $this->addSql('ALTER TABLE nines_blog_page RENAME INDEX blog_page_content TO blog_page_ft');
        $this->addSql('ALTER TABLE nines_blog_post DROP FOREIGN KEY FK_BA5AE01D12469DE2');
        $this->addSql('ALTER TABLE nines_blog_post DROP FOREIGN KEY FK_BA5AE01D6BF700BD');
        $this->addSql('ALTER TABLE nines_blog_post DROP FOREIGN KEY FK_BA5AE01DA76ED395');
        $this->addSql('ALTER TABLE nines_blog_post ADD CONSTRAINT FK_6D7DFE6A12469DE2 FOREIGN KEY (category_id) REFERENCES nines_blog_post_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nines_blog_post ADD CONSTRAINT FK_6D7DFE6A6BF700BD FOREIGN KEY (status_id) REFERENCES nines_blog_post_status (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nines_blog_post ADD CONSTRAINT FK_6D7DFE6AA76ED395 FOREIGN KEY (user_id) REFERENCES nines_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nines_blog_post RENAME INDEX idx_ba5ae01d12469de2 TO IDX_6D7DFE6A12469DE2');
        $this->addSql('ALTER TABLE nines_blog_post RENAME INDEX idx_ba5ae01d6bf700bd TO IDX_6D7DFE6A6BF700BD');
        $this->addSql('ALTER TABLE nines_blog_post RENAME INDEX idx_ba5ae01da76ed395 TO IDX_6D7DFE6AA76ED395');
        $this->addSql('ALTER TABLE nines_blog_post RENAME INDEX blog_post_content TO blog_post_ft');
        $this->addSql('ALTER TABLE nines_blog_post_category CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE nines_blog_post_category RENAME INDEX idx_ca275a0cea750e8 TO IDX_32F5FC8CEA750E8');
        $this->addSql('ALTER TABLE nines_blog_post_category RENAME INDEX idx_ca275a0c6de44026 TO IDX_32F5FC8C6DE44026');
        $this->addSql('ALTER TABLE nines_blog_post_category RENAME INDEX idx_ca275a0cea750e86de44026 TO IDX_32F5FC8CEA750E86DE44026');
        $this->addSql('ALTER TABLE nines_blog_post_category RENAME INDEX uniq_ca275a0c5e237e06 TO UNIQ_32F5FC8C5E237E06');
        $this->addSql('ALTER TABLE nines_blog_post_status CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE nines_blog_post_status RENAME INDEX idx_92121d87ea750e8 TO IDX_4A63E2FDEA750E8');
        $this->addSql('ALTER TABLE nines_blog_post_status RENAME INDEX idx_92121d876de44026 TO IDX_4A63E2FD6DE44026');
        $this->addSql('ALTER TABLE nines_blog_post_status RENAME INDEX idx_92121d87ea750e86de44026 TO IDX_4A63E2FDEA750E86DE44026');
        $this->addSql('ALTER TABLE nines_blog_post_status RENAME INDEX uniq_92121d875e237e06 TO UNIQ_4A63E2FD5E237E06');
        $this->addSql('ALTER TABLE nines_feedback_comment DROP FOREIGN KEY FK_9474526C6BF700BD');
        $this->addSql('ALTER TABLE nines_feedback_comment ADD CONSTRAINT FK_DD5C8DB56BF700BD FOREIGN KEY (status_id) REFERENCES nines_feedback_comment_status (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nines_feedback_comment RENAME INDEX idx_9474526c6bf700bd TO IDX_DD5C8DB56BF700BD');
        $this->addSql('ALTER TABLE nines_feedback_comment RENAME INDEX comment_ft_idx TO comment_ft');
        $this->addSql('ALTER TABLE nines_feedback_comment_note DROP FOREIGN KEY FK_E98B58F8A76ED395');
        $this->addSql('ALTER TABLE nines_feedback_comment_note DROP FOREIGN KEY FK_E98B58F8F8697D13');
        $this->addSql('ALTER TABLE nines_feedback_comment_note ADD CONSTRAINT FK_4BC0F0BA76ED395 FOREIGN KEY (user_id) REFERENCES nines_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nines_feedback_comment_note ADD CONSTRAINT FK_4BC0F0BF8697D13 FOREIGN KEY (comment_id) REFERENCES nines_feedback_comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nines_feedback_comment_note RENAME INDEX idx_e98b58f8a76ed395 TO IDX_4BC0F0BA76ED395');
        $this->addSql('ALTER TABLE nines_feedback_comment_note RENAME INDEX idx_e98b58f8f8697d13 TO IDX_4BC0F0BF8697D13');
        $this->addSql('ALTER TABLE nines_feedback_comment_note RENAME INDEX commentnote_ft_idx TO comment_note_ft');
        $this->addSql('ALTER TABLE nines_feedback_comment_status CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE nines_feedback_comment_status RENAME INDEX idx_b1133d0eea750e8 TO IDX_7B8DA610EA750E8');
        $this->addSql('ALTER TABLE nines_feedback_comment_status RENAME INDEX idx_b1133d0e6de44026 TO IDX_7B8DA6106DE44026');
        $this->addSql('ALTER TABLE nines_feedback_comment_status RENAME INDEX idx_b1133d0eea750e86de44026 TO IDX_7B8DA610EA750E86DE44026');
        $this->addSql('ALTER TABLE nines_feedback_comment_status RENAME INDEX uniq_b1133d0e5e237e06 TO UNIQ_7B8DA6105E237E06');
        $this->addSql('ALTER TABLE nines_media_audio ADD checksum VARCHAR(32) DEFAULT NULL, ADD source_url LONGTEXT DEFAULT NULL, DROP public, CHANGE entity entity VARCHAR(120) NOT NULL');
        $this->addSql('CREATE FULLTEXT INDEX nines_media_audio_ft ON nines_media_audio (original_name, description)');
        $this->addSql('CREATE INDEX IDX_9D15F751E284468 ON nines_media_audio (entity)');
        $this->addSql('CREATE INDEX IDX_9D15F751DE6FDF9A ON nines_media_audio (checksum)');
        $this->addSql('CREATE FULLTEXT INDEX IDX_9D15F751A58240EF ON nines_media_audio (source_url)');
        $this->addSql('ALTER TABLE nines_media_image ADD checksum VARCHAR(32) DEFAULT NULL, ADD source_url LONGTEXT DEFAULT NULL, DROP public, CHANGE entity entity VARCHAR(120) NOT NULL');
        $this->addSql('CREATE INDEX IDX_4055C59BE284468 ON nines_media_image (entity)');
        $this->addSql('CREATE INDEX IDX_4055C59BDE6FDF9A ON nines_media_image (checksum)');
        $this->addSql('CREATE FULLTEXT INDEX IDX_4055C59BA58240EF ON nines_media_image (source_url)');
        $this->addSql('ALTER TABLE nines_media_image RENAME INDEX idx_c53d045f545615306de44026 TO nines_media_image_ft');
        $this->addSql('ALTER TABLE nines_media_link CHANGE entity entity VARCHAR(120) NOT NULL, CHANGE text text VARCHAR(191) DEFAULT NULL');
        $this->addSql('ALTER TABLE nines_media_link RENAME INDEX idx_36ac99f1f47645ae3b8ba7c7 TO nines_media_link_ft');
        $this->addSql('ALTER TABLE nines_media_link RENAME INDEX idx_36ac99f1e284468 TO IDX_3B5D85A3E284468');
        $this->addSql('ALTER TABLE nines_media_pdf ADD checksum VARCHAR(32) DEFAULT NULL, ADD source_url LONGTEXT DEFAULT NULL, DROP public, CHANGE entity entity VARCHAR(120) NOT NULL');
        $this->addSql('CREATE INDEX IDX_9286B706E284468 ON nines_media_pdf (entity)');
        $this->addSql('CREATE INDEX IDX_9286B706DE6FDF9A ON nines_media_pdf (checksum)');
        $this->addSql('CREATE FULLTEXT INDEX IDX_9286B706A58240EF ON nines_media_pdf (source_url)');
        $this->addSql('ALTER TABLE nines_media_pdf RENAME INDEX idx_ef0db8c545615306de44026 TO nines_media_pdf_ft');
        $this->addSql('ALTER TABLE role CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE subject CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE subject_source CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE work_type CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
    }

    public function down(Schema $schema) : void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subject CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('DROP INDEX IDX_4055C59BE284468 ON nines_media_image');
        $this->addSql('DROP INDEX IDX_4055C59BDE6FDF9A ON nines_media_image');
        $this->addSql('DROP INDEX IDX_4055C59BA58240EF ON nines_media_image');
        $this->addSql('ALTER TABLE nines_media_image ADD public TINYINT(1) NOT NULL, DROP checksum, DROP source_url, CHANGE entity entity VARCHAR(128) NOT NULL');
        $this->addSql('ALTER TABLE nines_media_image RENAME INDEX nines_media_image_ft TO IDX_C53D045F545615306DE44026');
        $this->addSql('ALTER TABLE nines_media_link CHANGE text text VARCHAR(200) DEFAULT NULL, CHANGE entity entity VARCHAR(128) NOT NULL');
        $this->addSql('ALTER TABLE nines_media_link RENAME INDEX idx_3b5d85a3e284468 TO IDX_36AC99F1E284468');
        $this->addSql('ALTER TABLE nines_media_link RENAME INDEX nines_media_link_ft TO IDX_36AC99F1F47645AE3B8BA7C7');
        $this->addSql('ALTER TABLE nines_blog_post DROP FOREIGN KEY FK_6D7DFE6A12469DE2');
        $this->addSql('ALTER TABLE nines_blog_post DROP FOREIGN KEY FK_6D7DFE6A6BF700BD');
        $this->addSql('ALTER TABLE nines_blog_post DROP FOREIGN KEY FK_6D7DFE6AA76ED395');
        $this->addSql('ALTER TABLE nines_blog_post ADD CONSTRAINT FK_BA5AE01D12469DE2 FOREIGN KEY (category_id) REFERENCES nines_blog_post_category (id)');
        $this->addSql('ALTER TABLE nines_blog_post ADD CONSTRAINT FK_BA5AE01D6BF700BD FOREIGN KEY (status_id) REFERENCES nines_blog_post_status (id)');
        $this->addSql('ALTER TABLE nines_blog_post ADD CONSTRAINT FK_BA5AE01DA76ED395 FOREIGN KEY (user_id) REFERENCES nines_user (id)');
        $this->addSql('ALTER TABLE nines_blog_post RENAME INDEX idx_6d7dfe6aa76ed395 TO IDX_BA5AE01DA76ED395');
        $this->addSql('ALTER TABLE nines_blog_post RENAME INDEX blog_post_ft TO blog_post_content');
        $this->addSql('ALTER TABLE nines_blog_post RENAME INDEX idx_6d7dfe6a12469de2 TO IDX_BA5AE01D12469DE2');
        $this->addSql('ALTER TABLE nines_blog_post RENAME INDEX idx_6d7dfe6a6bf700bd TO IDX_BA5AE01D6BF700BD');
        $this->addSql('ALTER TABLE work_type CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('DROP INDEX nines_media_audio_ft ON nines_media_audio');
        $this->addSql('DROP INDEX IDX_9D15F751E284468 ON nines_media_audio');
        $this->addSql('DROP INDEX IDX_9D15F751DE6FDF9A ON nines_media_audio');
        $this->addSql('DROP INDEX IDX_9D15F751A58240EF ON nines_media_audio');
        $this->addSql('ALTER TABLE nines_media_audio ADD public TINYINT(1) NOT NULL, DROP checksum, DROP source_url, CHANGE entity entity VARCHAR(128) NOT NULL');
        $this->addSql('ALTER TABLE genre CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE nines_blog_post_category CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE nines_blog_post_category RENAME INDEX idx_32f5fc8cea750e8 TO IDX_CA275A0CEA750E8');
        $this->addSql('ALTER TABLE nines_blog_post_category RENAME INDEX uniq_32f5fc8c5e237e06 TO UNIQ_CA275A0C5E237E06');
        $this->addSql('ALTER TABLE nines_blog_post_category RENAME INDEX idx_32f5fc8c6de44026 TO IDX_CA275A0C6DE44026');
        $this->addSql('ALTER TABLE nines_blog_post_category RENAME INDEX idx_32f5fc8cea750e86de44026 TO IDX_CA275A0CEA750E86DE44026');
        $this->addSql('ALTER TABLE nines_feedback_comment_status CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE nines_feedback_comment_status RENAME INDEX idx_7b8da6106de44026 TO IDX_B1133D0E6DE44026');
        $this->addSql('ALTER TABLE nines_feedback_comment_status RENAME INDEX idx_7b8da610ea750e86de44026 TO IDX_B1133D0EEA750E86DE44026');
        $this->addSql('ALTER TABLE nines_feedback_comment_status RENAME INDEX idx_7b8da610ea750e8 TO IDX_B1133D0EEA750E8');
        $this->addSql('ALTER TABLE nines_feedback_comment_status RENAME INDEX uniq_7b8da6105e237e06 TO UNIQ_B1133D0E5E237E06');
        $this->addSql('ALTER TABLE nines_feedback_comment_note DROP FOREIGN KEY FK_4BC0F0BA76ED395');
        $this->addSql('ALTER TABLE nines_feedback_comment_note DROP FOREIGN KEY FK_4BC0F0BF8697D13');
        $this->addSql('ALTER TABLE nines_feedback_comment_note ADD CONSTRAINT FK_E98B58F8A76ED395 FOREIGN KEY (user_id) REFERENCES nines_user (id)');
        $this->addSql('ALTER TABLE nines_feedback_comment_note ADD CONSTRAINT FK_E98B58F8F8697D13 FOREIGN KEY (comment_id) REFERENCES nines_feedback_comment (id)');
        $this->addSql('ALTER TABLE nines_feedback_comment_note RENAME INDEX idx_4bc0f0ba76ed395 TO IDX_E98B58F8A76ED395');
        $this->addSql('ALTER TABLE nines_feedback_comment_note RENAME INDEX idx_4bc0f0bf8697d13 TO IDX_E98B58F8F8697D13');
        $this->addSql('ALTER TABLE nines_feedback_comment_note RENAME INDEX comment_note_ft TO commentnote_ft_idx');
        $this->addSql('ALTER TABLE date_category CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE nines_blog_post_status CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE nines_blog_post_status RENAME INDEX idx_4a63e2fdea750e8 TO IDX_92121D87EA750E8');
        $this->addSql('ALTER TABLE nines_blog_post_status RENAME INDEX uniq_4a63e2fd5e237e06 TO UNIQ_92121D875E237E06');
        $this->addSql('ALTER TABLE nines_blog_post_status RENAME INDEX idx_4a63e2fd6de44026 TO IDX_92121D876DE44026');
        $this->addSql('ALTER TABLE nines_blog_post_status RENAME INDEX idx_4a63e2fdea750e86de44026 TO IDX_92121D87EA750E86DE44026');
        $this->addSql('ALTER TABLE subject_source CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('DROP INDEX IDX_9286B706E284468 ON nines_media_pdf');
        $this->addSql('DROP INDEX IDX_9286B706DE6FDF9A ON nines_media_pdf');
        $this->addSql('DROP INDEX IDX_9286B706A58240EF ON nines_media_pdf');
        $this->addSql('ALTER TABLE nines_media_pdf ADD public TINYINT(1) NOT NULL, DROP checksum, DROP source_url, CHANGE entity entity VARCHAR(128) NOT NULL');
        $this->addSql('ALTER TABLE nines_media_pdf RENAME INDEX nines_media_pdf_ft TO IDX_EF0DB8C545615306DE44026');
        $this->addSql('ALTER TABLE role CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE nines_feedback_comment DROP FOREIGN KEY FK_DD5C8DB56BF700BD');
        $this->addSql('ALTER TABLE nines_feedback_comment ADD CONSTRAINT FK_9474526C6BF700BD FOREIGN KEY (status_id) REFERENCES nines_feedback_comment_status (id)');
        $this->addSql('ALTER TABLE nines_feedback_comment RENAME INDEX idx_dd5c8db56bf700bd TO IDX_9474526C6BF700BD');
        $this->addSql('ALTER TABLE nines_feedback_comment RENAME INDEX comment_ft TO comment_ft_idx');
        $this->addSql('ALTER TABLE nines_blog_page DROP FOREIGN KEY FK_23FD24C7A76ED395');
        $this->addSql('ALTER TABLE nines_blog_page ADD CONSTRAINT FK_F4DA3AB0A76ED395 FOREIGN KEY (user_id) REFERENCES nines_user (id)');
        $this->addSql('ALTER TABLE nines_blog_page RENAME INDEX idx_23fd24c7a76ed395 TO IDX_F4DA3AB0A76ED395');
        $this->addSql('ALTER TABLE nines_blog_page RENAME INDEX blog_page_ft TO blog_page_content');
    }
}
