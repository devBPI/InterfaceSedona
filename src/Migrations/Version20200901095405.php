<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200901095405 extends AbstractMigration
{
	public function getDescription() : string
	{
		return 'Updated Essentiels';
	}

	public function up(Schema $schema) : void
	{
	// this up() migration is auto-generated, please modify it to your needs
	$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

	$this->addSql('ALTER TABLE notice_availability_request ADD COLUMN status VARCHAR(128) DEFAULT \'TO_DO\'');
	$this->addSql('CREATE SEQUENCE notice_avail_rqst_id_seq');
	$this->addSql('ALTER TABLE notice_availability_request ALTER COLUMN id SET DEFAULT nextval(\'notice_avail_rqst_id_seq\')');

	/*********************/
	/* ACTUALITES-REVUES */
	/*********************/

	$url_tl1 = 'actualites-revues/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=0%2844%29&advanced_search%5B0%5D%5Bfield%5D=indiceCote&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&filters%5Bmaterial_support%5D%5B%5D=En+ligne&filters%5Bmaterial_support%5D%5B%5D=Papier&action=';
	$query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "$url_tl1", "es": "$url_tl1", "fr": "$url_tl1"}' WHERE id = 1;
SQL;
		$this->addSql($query);

	$url_tl2 = 'actualites-revues/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=0%28*&advanced_search%5B0%5D%5Bfield%5D=indiceCote&advanced_search_operator%5B1%5D=notCriteria&advanced_search%5B1%5D%5Btext%5D=0%2844%29&advanced_search%5B1%5D%5Bfield%5D=indiceCote&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&filters%5Bmaterial_support%5D%5B%5D=En+ligne&filters%5Bmaterial_support%5D%5B%5D=Papier&action=';
	$query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "$url_tl2", "es": "$url_tl2", "fr": "$url_tl_tl2"}' WHERE id = 2;
SQL;
		$this->addSql($query);

	$url_tl3 = 'actualites-revues/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=07*&advanced_search%5B0%5D%5Bfield%5D=indiceCote&advanced_search_operator%5B1%5D=or&advanced_search%5B1%5D%5Btext%5D=09*&advanced_search%5B1%5D%5Bfield%5D=indiceCote&advanced_search_operator%5B2%5D=or&advanced_search%5B2%5D%5Btext%5D=02*&advanced_search%5B2%5D%5Bfield%5D=indiceCote&advanced_search_operator%5B3%5D=or&advanced_search%5B3%5D%5Btext%5D=03*&advanced_search%5B3%5D%5Bfield%5D=indiceCote&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&filters%5Btype%5D%5B%5D=Revue%2C+journal&filters%5Btype%5D%5B%5D=Revue+num%C3%A9rique&action=';
	$query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "$url_tl3", "es": "$url_tl3", "fr": "$url_tl3"}' WHERE id = 3;
SQL;
		$this->addSql($query);

	$url_t2 = 'actualites-revues/recherche-avancee?advanced_search[0][text]=407615+747667+1409967+1401427+875730+1411060&advanced_search[0][field]=sourceId&filters[configuration_name][]=Catalogue+Bpi&adv-search-date=Date&criteria[publicationDate]=&criteria[publicationDateStart]=&criteria[publicationDateEnd]';
	$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url_t2", "es": "$url_t2", "fr": "$url_t2"}' WHERE id = 2;
SQL;
		$this->addSql($query);

	$url_tl4 = 'actualites-revues/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=&advanced_search%5B0%5D%5Bfield%5D=general&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&filters%5Bmaterial_support%5D%5B%5D=Microfiches&filters%5Bmaterial_support%5D%5B%5D=Microfilms&filters%5Bsecteur%5D%5B%5D=Presse&action=';
	$query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "$url_tl4", "es": "$url_tl4", "fr": "$url_tl4"}' WHERE id = 4;
SQL;
		$this->addSql($query);

	$url_tl5 = 'actualites-revues/recherche-simple?mot=%22Bpi-doc%22&type=title&action=';
	$query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "$url_tl5", "es": "$url_tl5", "fr": "$url_tl5"}' WHERE id = 5;
SQL;
		$this->addSql($query);

	$url_tl6 = 'actualites-revues/recherche-simple?mot=%22Gallica+%3A+Presse+et+revues%22&type=title&action=';
	$query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "$url_tl6", "es": "$url_tl6", "fr": "$url_tl5"}' WHERE id = 6;
SQL;
		$this->addSql($query);

	$url_t4 = 'actualites-revues/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=07*&advanced_search%5B0%5D%5Bfield%5D=indiceCote&advanced_search_operator%5B1%5D=or&advanced_search%5B1%5D%5Btext%5D=09*&advanced_search%5B1%5D%5Bfield%5D=indiceCote&advanced_search_operator%5B2%5D=or&advanced_search%5B2%5D%5Btext%5D=02*&advanced_search%5B2%5D%5Bfield%5D=indiceCote&advanced_search_operator%5B3%5D=or&advanced_search%5B3%5D%5Btext%5D=03*&advanced_search%5B3%5D%5Bfield%5D=indiceCote&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&filters%5Btype%5D%5B%5D=Livre&filters%5Btype%5D%5B%5D=Livre+num%C3%A9rique&action=';
	$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url_t4", "es": "$url_t4", "fr": "$url_t4"}' WHERE id = 4;
SQL;
		$this->addSql($query);

	$url_t5 = 'actualites-revues/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=EMP*&advanced_search%5B0%5D%5Bfield%5D=indiceCote&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&filters%5Btype%5D%5B%5D=Revue%2C+journal&filters%5Btype%5D%5B%5D=Revue+num%C3%A9rique&action=';
	$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url_t5", "es": "$url_t5", "fr": "$url_t5"}' WHERE id = 5;
SQL;
		$this->addSql($query);

	$url_tl7 = 'actualites-revues/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=794%2A&advanced_search%5B0%5D%5Bfield%5D=indiceCote&advanced_search%5B1%5D%5Btext%5D=795%2A&advanced_search%5B1%5D%5Bfield%5D=indiceCote&advanced_search%5B2%5D%5Btext%5D=796%2A&advanced_search%5B2%5D%5Bfield%5D=indiceCote&advanced_search%5B3%5D%5Btext%5D=797%2A&advanced_search%5B3%5D%5Bfield%5D=indiceCote&advanced_search%5B4%5D%5Btext%5D=PRA+H&advanced_search%5B4%5D%5Bfield%5D=indiceCote&advanced_search_operator%5B1%5D=or&advanced_search_operator%5B2%5D=or&advanced_search_operator%5B3%5D=or&advanced_search_operator%5B4%5D=or&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&filters%5Btype%5D%5B0%5D=Revue%2C+journal&filters%5Btype%5D%5B1%5D=Revue+num%C3%A9rique&action=';
	$query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "$url_tl7", "es": "$url_tl7", "fr": "$url_tl7"}' WHERE id = 7;
SQL;
		$this->addSql($query);

	$url_tl8 = 'actualites-revues/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=PRA*&advanced_search%5B0%5D%5Bfield%5D=indiceCote&advanced_search_operator%5B1%5D=notCriteria&advanced_search%5B1%5D%5Btext%5D=%22PRA+H%22&advanced_search%5B1%5D%5Bfield%5D=indiceCote&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&filters%5Btype%5D%5B%5D=Revue%2C+journal&filters%5Btype%5D%5B%5D=Revue+num%C3%A9rique&action=';
	$query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "$url_tl8", "es": "$url_tl8", "fr": "$url_tl8"}' WHERE id = 8;
SQL;
		$this->addSql($query);

	/*****************/
	/* AUTOFORMATION */
	/*****************/

	$url_tl9 = 'actualites-revues/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=SG*&advanced_search%5B0%5D%5Bfield%5D=indiceCote&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&action=';
	$query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "$url_tl9", "es": "$url_tl9", "fr": "$url_tl9"}' WHERE id = 9;
SQL;
		$this->addSql($query);

	$url_tl10 = 'actualites-revues/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=64*&advanced_search%5B0%5D%5Bfield%5D=indiceCote&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&action=';
	$query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "$url_tl10", "es": "$url_tl10", "fr": "$url_tl10"}' WHERE id = 10;
SQL;
		$this->addSql($query);

	$url_t7 = 'actualites-revues/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=78%280%29&advanced_search%5B0%5D%5Bfield%5D=indiceCote&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&action=';
	$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url_t7", "es": "$url_t7", "fr": "$url_t7"}' WHERE id = 7;
SQL;
		$this->addSql($query);

	$url_t8 = 'actualites-revues/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=79%280%29&advanced_search%5B0%5D%5Bfield%5D=indiceCote&advanced_search_operator%5B1%5D=or&advanced_search%5B1%5D%5Btext%5D=791%280%29&advanced_search%5B1%5D%5Bfield%5D=indiceCote&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&action=';
	$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url_t8", "es": "$url_t8", "fr": "$url_t8"}' WHERE id = 8;
SQL;
		$this->addSql($query);

	$url_t9 = 'autoformation/recherche-simple?mot=%22Langues+%3E+Fran%C3%A7ais+%3E+Fran%C3%A7ais+langue+%C3%A9trang%C3%A8re+%3E+Initiation%22&type=theme&action=';
	$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url_t9", "es": "$url_t9", "fr": "$url_t9"}' WHERE id = 9;
SQL;
		$this->addSql($query);

	$url_tl11 = 'autoformation/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=%22Langues+%3E+Langues+%C3%A9trang%C3%A8res+et+dialectes+%3E+Anglais+britannique%22&advanced_search%5B0%5D%5Bfield%5D=theme&advanced_search_operator%5B1%5D=or&advanced_search%5B1%5D%5Btext%5D=%22Langues+%3E+Langues+%C3%A9trang%C3%A8res+et+dialectes+%3E+Anglais+am%C3%A9ricain%22&advanced_search%5B1%5D%5Bfield%5D=theme&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&action=';
	$query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "$url_tl11", "es": "$url_tl11", "fr": "$url_tl11"}' WHERE id = 11;
SQL;
		$this->addSql($query);

	$url_tl12 = 'autoformation/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=%22Langues+%3E+Langues+%C3%A9trang%C3%A8res+et+dialectes+%3E+Espagnol%22&advanced_search%5B0%5D%5Bfield%5D=theme&advanced_search_operator%5B1%5D=or&advanced_search%5B1%5D%5Btext%5D=%22Langues+%3E+Langues+%C3%A9trang%C3%A8res+et+dialectes+%3E+Espagnol+d%27Am%C3%A9rique+latine%22&advanced_search%5B1%5D%5Bfield%5D=theme&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&action=';
	$query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "$url_tl12", "es": "$url_tl12", "fr": "$url_tl12"}' WHERE id = 12;
SQL;
		$this->addSql($query);

	$url_tl13 = 'autoformation/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=%22Langues+%3E+Langues+%C3%A9trang%C3%A8res+et+dialectes+%3E+Italien%22&advanced_search%5B0%5D%5Bfield%5D=theme&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&action=';
	$query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "$url_tl13", "es": "$url_tl13", "fr": "$url_tl13"}' WHERE id = 13;
SQL;
		$this->addSql($query);

	$url_tl14 = 'autoformation/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=%22Langues+%3E+Langues+%C3%A9trang%C3%A8res+et+dialectes+%3E+Anglais+britannique+%3E+Tests+et+examens%22&advanced_search%5B0%5D%5Bfield%5D=theme&advanced_search_operator%5B1%5D=or&advanced_search%5B1%5D%5Btext%5D=%22Langues+%3E+Langues+%C3%A9trang%C3%A8res+et+dialectes+%3E+Anglais+am%C3%A9ricain+%3E+Tests+et+examens%22&advanced_search%5B1%5D%5Bfield%5D=theme&advanced_search_operator%5B2%5D=or&advanced_search%5B2%5D%5Btext%5D=%22Examens%2C+concours%2C+tests+%3E+Tests+et+examens+de+langue+%3E+Anglais+britannique+et+am%C3%A9ricain%22&advanced_search%5B2%5D%5Bfield%5D=theme&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&action=';
	$query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "$url_tl14", "es": "$url_tl14", "fr": "$url_tl14"}' WHERE id = 14;
SQL;
		$this->addSql($query);

	$url_tl15 = 'autoformation/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=%22Langues+%3E+Fran%C3%A7ais+%3E+Fran%C3%A7ais+langue+%C3%A9trang%C3%A8re+%3E+Tests+et+examens%22&advanced_search%5B0%5D%5Bfield%5D=theme&advanced_search_operator%5B1%5D=or&advanced_search%5B1%5D%5Btext%5D=%22Examens%2C+concours%2C+tests+%3E+Tests+et+examens+de+langue+%3E+Fran%C3%A7ais+langue+%C3%A9trang%C3%A8re%22&advanced_search%5B1%5D%5Bfield%5D=theme&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&action=';
	$query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "$url_tl15", "es": "$url_tl15", "fr": "$url_tl15"}' WHERE id = 15;
SQL;
		$this->addSql($query);

	$url_tl16 = 'autoformation/recherche-avancee?advanced_search[0][text]=%22Tests+et+examens%22&advanced_search[0][field]=theme&advanced_search_operator[1]=and&advanced_search[1][text]=langue&advanced_search[1][field]=theme&advanced_search_operator[2]=notCriteria&advanced_search[2][text]=anglais+français&advanced_search[2][field]=general&adv-search-date=Date&criteria[publicationDate]=&criteria[publicationDateStart]=&criteria[publicationDateEnd]=&action=';
	$query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "$url_tl16", "es": "$url_tl16", "fr": "$url_tl16"}' WHERE id = 16;
SQL;
		$this->addSql($query);

	$url_tl17 = 'autoformation/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=%22Examens%2C+concours%2C+tests+%3E+Tests+psychotechniques%2C+tests+de+recrutement%22&advanced_search%5B0%5D%5Bfield%5D=theme&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&action=';
	$query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "$url_tl17", "es": "$url_tl17", "fr": "$url_tl17"}' WHERE id = 17;
SQL;
		$this->addSql($query);

	$url_t12 = 'autoformation/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=%22Informatique%2C+Internet+%3E+Informatique+%3E+Initiation%22&advanced_search%5B0%5D%5Bfield%5D=theme&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&action=';
	$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url_t12", "es": "$url_t12", "fr": "$url_t12"}' WHERE id = 12;
SQL;
		$this->addSql($query);

	$url_tl18 = 'autoformation/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=%22Informatique%2C+Internet+%3E+Bureautique+-+didacticiels+%3E+Traitements+de+textes%22&advanced_search%5B0%5D%5Bfield%5D=theme&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&action=';
	$query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "$url_tl18", "es": "$url_tl18", "fr": "$url_tl18"}' WHERE id = 18;
SQL;
		$this->addSql($query);

	$url_tl19 = 'autoformation/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=%22Informatique%2C+Internet+%3E+Bureautique+-+didacticiels+%3E+Tableurs%22&advanced_search%5B0%5D%5Bfield%5D=theme&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&action=';
	$query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "$url_tl19", "es": "$url_tl19", "fr": "$url_tl19"}' WHERE id = 19;
SQL;
		$this->addSql($query);

	$url_tl20 = 'autoformation/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=%22Informatique%2C+Internet+%3E+Bureautique+-+didacticiels+%3E+Pr%C3%A9sentations+assist%C3%A9es+par+ordinateur%22&advanced_search%5B0%5D%5Bfield%5D=theme&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&action=';
	$query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "$url_tl20", "es": "$url_tl20", "fr": "$url_tl20"}' WHERE id = 20;
SQL;
		$this->addSql($query);

	$url_tl21 = 'autoformation/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=%22Informatique%2C+Internet+%3E+Bureautique+-+didacticiels+%3E+Gestionnaires+de+base+de+donn%C3%A9es%22&advanced_search%5B0%5D%5Bfield%5D=theme&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&action=';
	$query = <<<SQL
UPDATE public.theme_level SET url = '{"en": "$url_tl21", "es": "$url_tl21", "fr": "$url_tl21"}' WHERE id = 21;
SQL;
		$this->addSql($query);

	$url_t14 = 'autoformation/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=Informatique+Internet&advanced_search%5B0%5D%5Bfield%5D=theme&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&action=';
	$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url_t14", "es": "$url_t14", "fr": "$url_t14"}' WHERE id = 14;
SQL;
		$this->addSql($query);

	$url_t15 = 'autoformation/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=%22Code+de+la+route%2C+permis+de+conduire%22&advanced_search%5B0%5D%5Bfield%5D=theme&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&action=';
	$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url_t15", "es": "$url_t15", "fr": "$url_t15"}' WHERE id = 15;
SQL;
		$this->addSql($query);

	$url_t16 = 'autoformation/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=%22Documents+p%C3%A9dagogiques+%3E+Vid%C3%A9o%22&advanced_search%5B0%5D%5Bfield%5D=theme&advanced_search_operator%5B1%5D=or&advanced_search%5B1%5D%5Btext%5D=%22Langues%2C+linguistique+%3E+Films+d%27autoformation%22&advanced_search%5B1%5D%5Bfield%5D=theme&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&action=';
	$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url_t16", "es": "$url_t16", "fr": "$url_t16"}' WHERE id = 16;
SQL;
		$this->addSql($query);

	$url_t17 = 'autoformation/recherche-avancee?advanced_search[0][text]=%22MOOC%22&advanced_search[0][field]=title&advanced_search_operator[1]=or&advanced_search[1][text]=%22Consultation+libre+sur+le+web%22&advanced_search[1][field]=droits&adv-search-date=Date&criteria[publicationDate]=&criteria[publicationDateStart]=&criteria[publicationDateEnd]=&action=';
	$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url_t17", "es": "$url_t17", "fr": "$url_t17"}' WHERE id = 17;
SQL;
		$this->addSql($query);

	$url_t18 = 'autoformation/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=Economie%2C+entreprise%2C+gestion&advanced_search%5B0%5D%5Bfield%5D=theme&advanced_search_operator%5B1%5D=or&advanced_search%5B1%5D%5Btext%5D=Recherche+d%27emploi%2C+m%C3%A9tiers%2C+formation+%3E+Recherche+d%27emploi&advanced_search%5B1%5D%5Bfield%5D=theme&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&action=';
	$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url_t18", "es": "$url_t18", "fr": "$url_t18"}' WHERE id = 18;
SQL;
		$this->addSql($query);

	$url_t19 = 'autoformation/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=%22D%C3%A9veloppement+des+comp%C3%A9tences%22&advanced_search%5B0%5D%5Bfield%5D=theme&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&action=';
	$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url_t19", "es": "$url_t19", "fr": "$url_t19"}' WHERE id = 19;
SQL;
		$this->addSql($query);

	$url_t20 = 'autoformation/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=Arts&advanced_search%5B0%5D%5Bfield%5D=theme&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&action=';
	$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url_t20", "es": "$url_t20", "fr": "$url_t20"}' WHERE id = 20;
SQL;
		$this->addSql($query);

	/**********/
	/* CINEMA */
	/**********/

	$url_t21 = 'cinema/recherche-avancee?advanced_search[0][text]=441505+1263007+906797+563868+522988+1208328+888539+328130+1037002+1158536+1343861+1312499+1542860+69322+1253103+1507825+1532599+107999+1548328+769893+273989+249732&advanced_search[0][field]=sourceId&filters[configuration_name][]=Catalogue+Bpi&adv-search-date=Date&criteria[publicationDate]=&criteria[publicationDateStart]=&criteria[publicationDateEnd]=';
	$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url_t21", "es": "$url_t21", "fr": "$url_t21"}' WHERE id = 21;
SQL;
		$this->addSql($query);

	$url_t22 = 'cinema/recherche-avancee?advanced_search[0][text]=791.24&advanced_search[0][field]=indiceCote&facets[type][]=Livre&facets[type][]=Livre+num%C3%A9rique&action=';
	$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url_t22", "es": "$url_t22", "fr": "$url_t22"}' WHERE id = 22;
SQL;
		$this->addSql($query);

	$url_t23 = 'cinema/recherche-avancee?advanced_search[0][text]=*&advanced_search[0][field]=recompenses&adv-search-date=Date&criteria[publicationDate]=&criteria[publicationDateStart]=&criteria[publicationDateEnd]=&filters[type][]=Vid%C3%A9o&action=';
	$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url_t23", "es": "$url_t23", "fr": "$url_t23"}' WHERE id = 23;
SQL;
		$this->addSql($query);

	$url_t24 = 'cinema/recherche-avancee?facets[genre_film][]=film+d%27animation&action=';
	$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url_t24", "es": "$url_t24", "fr": "$url_t24"}' WHERE id = 24;
SQL;
		$this->addSql($query);

	$url_t25 = 'cinema/recherche-avancee?facets[genre_film][]=spectacle%2C+concert&action=';
	$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url_t25", "es": "$url_t25", "fr": "$url_t25"}' WHERE id = 25;
SQL;
		$this->addSql($query);

	$url_t26 = 'cinema/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=%22Arts+%3E+Cin%C3%A9ma%22&advanced_search%5B0%5D%5Bfield%5D=theme&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&filters%5Btype%5D%5B%5D=Site+et+base&action=';
	$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url_t26", "es": "$url_t26", "fr": "$url_t26"}' WHERE id = 26;
SQL;
		$this->addSql($query);

	$url_t27 = 'cinema';
	$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url_t27", "es": "$url_t27", "fr": "$url_t27"}' WHERE id = 27;
SQL;
		$this->addSql($query);

	$url_t28 = 'cinema/recherche-avancee?advanced_search%5B0%5D%5Btext%5D=783.7*&advanced_search%5B0%5D%5Bfield%5D=indiceCote&advanced_search_operator%5B1%5D=or&advanced_search%5B1%5D%5Btext%5D=783.6*&advanced_search%5B1%5D%5Bfield%5D=indiceCote&advanced_search_operator%5B2%5D=or&advanced_search%5B2%5D%5Btext%5D=791.47*&advanced_search%5B2%5D%5Bfield%5D=indiceCote&adv-search-date=Date&criteria%5BpublicationDate%5D=&criteria%5BpublicationDateStart%5D=&criteria%5BpublicationDateEnd%5D=&filters%5Btype%5D%5B%5D=Livre&filters%5Btype%5D%5B%5D=Vid%C3%A9o&action=';
	$query = <<<SQL
UPDATE public.theme SET url = '{"en": "$url_t28", "es": "$url_t28", "fr": "$url_t28"}' WHERE id = 28;
SQL;
		$this->addSql($query);
	}

	public function down(Schema $schema) : void
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
	}
}
