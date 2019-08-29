<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190820100924 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE word_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_selection_document_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_selection_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE theme_level_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE theme_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE thematic_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE search_engine_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE notice_avail_rqst_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_history_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE word (id INT NOT NULL, title JSONB NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN word.title IS \'(DC2Type:json_array)\'');
        $this->addSql('CREATE TABLE user_selection_document (id INT NOT NULL, category_id INT DEFAULT NULL, title VARCHAR(250) NOT NULL, author VARCHAR(250) NOT NULL, document_type VARCHAR(50) DEFAULT NULL, creation_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, position INT NOT NULL, comment TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A38819C512469DE2 ON user_selection_document (category_id)');
        $this->addSql('CREATE TABLE user_selection_category (id INT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(250) NOT NULL, position INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7DAD8A72A76ED395 ON user_selection_category (user_id)');
        $this->addSql('CREATE TABLE theme_level (id INT NOT NULL, parent_id INT DEFAULT NULL, title JSONB NOT NULL, url JSONB NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C2C847F6727ACA70 ON theme_level (parent_id)');
        $this->addSql('COMMENT ON COLUMN theme_level.title IS \'(DC2Type:json_array)\'');
        $this->addSql('COMMENT ON COLUMN theme_level.url IS \'(DC2Type:json_array)\'');
        $this->addSql('CREATE TABLE theme (id INT NOT NULL, parent_id INT DEFAULT NULL, title JSONB NOT NULL, image VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9775E708727ACA70 ON theme (parent_id)');
        $this->addSql('COMMENT ON COLUMN theme.title IS \'(DC2Type:json_array)\'');
        $this->addSql('CREATE TABLE thematic (id INT NOT NULL, title JSONB NOT NULL, image VARCHAR(50) NOT NULL, description JSONB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN thematic.title IS \'(DC2Type:json_array)\'');
        $this->addSql('COMMENT ON COLUMN thematic.description IS \'(DC2Type:json_array)\'');
        $this->addSql('CREATE TABLE thematic_word (thematic_id INT NOT NULL, word_id INT NOT NULL, PRIMARY KEY(thematic_id, word_id))');
        $this->addSql('CREATE INDEX IDX_F54773842395FCED ON thematic_word (thematic_id)');
        $this->addSql('CREATE INDEX IDX_F5477384E357438D ON thematic_word (word_id)');
        $this->addSql('CREATE TABLE search_engine (id INT NOT NULL, name VARCHAR(100) NOT NULL, logo VARCHAR(100) NOT NULL, url VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_314A75925E237E06 ON search_engine (name)');
        $this->addSql('CREATE TABLE notice_availability_request (id BIGINT NOT NULL, notice_configuration_id BIGINT NOT NULL, notice_source_id VARCHAR(1024) NOT NULL, request_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, modification_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, requester VARCHAR(1024) DEFAULT NULL, notification_email VARCHAR(1024) DEFAULT NULL, comment VARCHAR(1024) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_history (id INT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(250) NOT NULL, queries JSONB NOT NULL, url VARCHAR(250) NOT NULL, creation_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7FB76E41A76ED395 ON user_history (user_id)');
        $this->addSql('COMMENT ON COLUMN user_history.queries IS \'(DC2Type:json_array)\'');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, ldap VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6493389DF81 ON "user" (ldap)');
        $this->addSql('ALTER TABLE user_selection_document ADD CONSTRAINT FK_A38819C512469DE2 FOREIGN KEY (category_id) REFERENCES user_selection_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_selection_category ADD CONSTRAINT FK_7DAD8A72A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE theme_level ADD CONSTRAINT FK_C2C847F6727ACA70 FOREIGN KEY (parent_id) REFERENCES theme (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE theme ADD CONSTRAINT FK_9775E708727ACA70 FOREIGN KEY (parent_id) REFERENCES thematic (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE thematic_word ADD CONSTRAINT FK_F54773842395FCED FOREIGN KEY (thematic_id) REFERENCES thematic (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE thematic_word ADD CONSTRAINT FK_F5477384E357438D FOREIGN KEY (word_id) REFERENCES word (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_history ADD CONSTRAINT FK_7FB76E41A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE thematic_word DROP CONSTRAINT FK_F5477384E357438D');
        $this->addSql('ALTER TABLE user_selection_document DROP CONSTRAINT FK_A38819C512469DE2');
        $this->addSql('ALTER TABLE theme_level DROP CONSTRAINT FK_C2C847F6727ACA70');
        $this->addSql('ALTER TABLE theme DROP CONSTRAINT FK_9775E708727ACA70');
        $this->addSql('ALTER TABLE thematic_word DROP CONSTRAINT FK_F54773842395FCED');
        $this->addSql('ALTER TABLE user_selection_category DROP CONSTRAINT FK_7DAD8A72A76ED395');
        $this->addSql('ALTER TABLE user_history DROP CONSTRAINT FK_7FB76E41A76ED395');
        $this->addSql('DROP SEQUENCE word_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_selection_document_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_selection_category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE theme_level_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE theme_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE thematic_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE search_engine_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE notice_avail_rqst_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_history_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('DROP TABLE word');
        $this->addSql('DROP TABLE user_selection_document');
        $this->addSql('DROP TABLE user_selection_category');
        $this->addSql('DROP TABLE theme_level');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP TABLE thematic');
        $this->addSql('DROP TABLE thematic_word');
        $this->addSql('DROP TABLE search_engine');
        $this->addSql('DROP TABLE notice_availability_request');
        $this->addSql('DROP TABLE user_history');
        $this->addSql('DROP TABLE "user"');
    }
}
