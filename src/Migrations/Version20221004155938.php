<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221004155938 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE theme_level SET code='AR-PRESSE-FRANCAISE' WHERE id = 1;");
        $this->addSql("UPDATE theme_level SET code='AR-PRESSE-ETRANGERE' WHERE id = 2;");
        $this->addSql("UPDATE theme_level SET code='AR-REVUES-MEDIAS' WHERE id = 3;");
        $this->addSql("UPDATE theme_level SET code='AR-MICROFILMS' WHERE id = 4;");
        $this->addSql("UPDATE theme_level SET code='AR-BPI-DOC' WHERE id = 5;");
        $this->addSql("UPDATE theme_level SET code='AR-GALLICA' WHERE id = 6;");
        $this->addSql("UPDATE theme_level SET code='AR-SPORTS-JEUX-TOURISME' WHERE id = 7;");
        $this->addSql("UPDATE theme_level SET code='AR-VIE-PRATIQUE' WHERE id = 8;");
        $this->addSql("UPDATE theme_level SET code='AR-CULTURE-GEEK' WHERE id = 9;");
        $this->addSql("UPDATE theme_level SET code='AR-DECO-BRICO-CUISINE' WHERE id = 10;");
        $this->addSql("UPDATE theme_level SET code='AUTOF-LANGUES-ANGLAIS' WHERE id = 11;");
        $this->addSql("UPDATE theme_level SET code='AUTOF-LANGUES-ESPAGNOL' WHERE id = 12;");
        $this->addSql("UPDATE theme_level SET code='AUTOF-LANGUES-ITALIEN' WHERE id = 13;");
        $this->addSql("UPDATE theme_level SET code='AUTOF-LANGUES-ANGLAIS-AMERICAIN' WHERE id = 14;");
        $this->addSql("UPDATE theme_level SET code='AUTOF-LANGUES-FRANCAIS' WHERE id = 15;");
        $this->addSql("UPDATE theme_level SET code='AUTOF-LANGUES-AUTRES' WHERE id = 16;");
        $this->addSql("UPDATE theme_level SET code='AUTOF-TESTS-PSYCHOTECHNIQUES' WHERE id = 17;");
        $this->addSql("UPDATE theme_level SET code='AUTOF-BUREAUTIQUE-WORD' WHERE id = 18;");
        $this->addSql("UPDATE theme_level SET code='AUTOF-BUREAUTIQUE-EXCEL' WHERE id = 19;");
        $this->addSql("UPDATE theme_level SET code='AUTOF-BUREAUTIQUE-POWERPOINT' WHERE id = 20;");
        $this->addSql("UPDATE theme_level SET code='AUTOF-BUREAUTIQUE-ACCESS' WHERE id = 21;");
        // theme
        $this->addSql("UPDATE theme SET code='AR-PRESSE-MEDIAS-EN-LIGNE' WHERE id = 2;");
        $this->addSql("UPDATE theme SET code='AR-LIVRES-MEDIAS' WHERE id = 4;");
        $this->addSql("UPDATE theme SET code='AR-REVUES-EMPLOI-FORMATION' WHERE id = 5;");
        $this->addSql("UPDATE theme SET code='AR-REVUES-MUSIQUE' WHERE id = 7;");
        $this->addSql("UPDATE theme SET code='AR-REVUES-CINEMA' WHERE id = 8;");
        $this->addSql("UPDATE theme SET code='AUTOF-DEBUTER-FRANCAIS' WHERE id = 9;");
        $this->addSql("UPDATE theme SET code='AUTOF-INFORMATIQUE' WHERE id = 12;");
        $this->addSql("UPDATE theme SET code='AUTOF-TECHNOLOGIES-NUMERIQUES' WHERE id = 14;");
        $this->addSql("UPDATE theme SET code='AUTOF-CODE-ROUTE' WHERE id = 15;");
        $this->addSql("UPDATE theme SET code='AUTOF-FILM-LANGUE-ORIGINALE' WHERE id = 16;");
        $this->addSql("UPDATE theme SET code='AUTOF-MOOCS-SITES-GRATUITS' WHERE id = 17;");
        $this->addSql("UPDATE theme SET code='AUTOF-MONDE-ENTREPRISE' WHERE id = 18;");
        $this->addSql("UPDATE theme SET code='AUTOF-DEVELOPPEMENT-PERSONNEL' WHERE id = 19;");
        $this->addSql("UPDATE theme SET code='AUTOF-EDUCATION-ARTISTIQUE' WHERE id = 20;");
        $this->addSql("UPDATE theme SET code='CINE-DOCUMENTAIRE-CHOIX' WHERE id = 21;");
        $this->addSql("UPDATE theme SET code='CINE-LIVRES-DOCUMENTARISTES' WHERE id = 22;");
        $this->addSql("UPDATE theme SET code='CINE-FILMS-DOCUMENTAIRES-PRIMES' WHERE id = 23;");
        $this->addSql("UPDATE theme SET code='CINE-FILMS-ANIMATION' WHERE id = 24;");
        $this->addSql("UPDATE theme SET code='CINE-SPECTACLES-CONCERTS' WHERE id = 25;");
        $this->addSql("UPDATE theme SET code='CINE-SITES-CINEMA' WHERE id = 26;");
        $this->addSql("UPDATE theme SET code='CINE-FILMS-FICTION-VIDEO-DEMANDE' WHERE id = 27;");
        $this->addSql("UPDATE theme SET code='CINE-MUSIQUES-FILMS' WHERE id = 28;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
