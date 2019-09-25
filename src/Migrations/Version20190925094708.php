<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190925094708 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE thematic ADD type VARCHAR(250) NOT NULL');
        $this->addSql('ALTER TABLE thematic ADD slug VARCHAR(250) NOT NULL');

        $this->addSql('ALTER TABLE theme ADD url JSONB NOT NULL');
        $this->addSql('COMMENT ON COLUMN theme.url IS \'(DC2Type:json_array)\'');


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE thematic DROP type');
        $this->addSql('ALTER TABLE thematic DROP slug');
        $this->addSql('ALTER TABLE theme DROP url');
    }
}
