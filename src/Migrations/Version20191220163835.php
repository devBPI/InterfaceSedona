<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191220163835 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('UPDATE public.theme_level SET title = \'{"en": "PowerPoint", "es": "PowerPoint", "fr": "PowerPoint"}\' WHERE id=20');
        $this->addSql('UPDATE public.theme_level SET title = \'{"en": "Déco, brico, cuisine", "es": "Déco, brico, cuisine", "fr": "Déco, brico, cuisine"}\' WHERE id=10');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('UPDATE public.theme_level SET title = \'{"en": "Powerpoint", "es": "Powerpoint", "fr": "Powerpoint"}\' WHERE id=20');
        $this->addSql('UPDATE public.theme_level SET title = \'{"en": "Déco – brico – cuisine", "es": "Déco – brico – cuisine", "fr": "Déco – brico – cuisine"}\' WHERE id=10');
    }
}
