<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191021161719 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql("update public.thematic set image = 'parcours-autoformation.jpg', description = json_build_object('fr',  'Apprendre, s''exercer, s''améliorer en langues, logiciels, préparer le code de la route ou un concours en autodidacte', 'en', 'Apprendre, s''exercer, s''améliorer en langues, logiciels, préparer le code de la route ou un concours en autodidacte', 'es', 'Apprendre, s''exercer, s''améliorer en langues, logiciels, préparer le code de la route ou un concours en autodidacte')   where type='autoformation'");
        $this->addSql("update public.thematic set image = 'parcours-presse.jpg' where type = 'presse'");
        $this->addSql("update public.thematic set image = 'parcours-cinema.jpg' where type = 'cinema'");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

    }
}
