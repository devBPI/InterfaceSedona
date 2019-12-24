<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191224121737 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'add theme links';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $url1 = 'cinema/recherche-avancee?advanced_search[0][text]=791.24&advanced_search[0][field]=indiceCote&facets[type][]=Livre&facets[type][]=Livre+numérique&action=';
$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url1", "es": "$url1", "fr": "$url1"}' WHERE id = 22;
SQL;
        $this->addSql($query);

        $url2 = 'cinema/recherche-avancee?facets[genre_film][]=film+d\'\'animation&action=';
$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url2", "es": "$url2", "fr": "$url2"}' WHERE id = 24;
SQL;
        $this->addSql($query);

        $url3 = 'cinema/recherche-avancee?facets[genre_film][]=spectacle%2C+concert&action=';
$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url3", "es": "$url3", "fr": "$url3"}' WHERE id = 25;
SQL;
        $this->addSql($query);

        $url4 = 'actualites-revues/recherche-avancee?facets[material_support][]=Microfilms&facets[material_support][]=Microfiches&facets[secteur][]=Presse&action=';
$query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "$url4", "es": "$url4", "fr": "$url4"}' WHERE id = 4;
SQL;

        $this->addSql($query);
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $query = <<<SQL
UPDATE public.theme SET url = '{"en": "", "es": "", "fr": ""}' WHERE id = 22 or id = 24 or id = 25;
SQL;
        $this->addSql($query);

        $query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "", "es": "", "fr": ""}' WHERE id = 4;
SQL;
        $this->addSql($query);

    }
}
