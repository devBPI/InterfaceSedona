Bibliothèque Publique d'Information - Interface catalogue
======================


1. [Présentation du projet](#pr%C3%A9sentation-du-projet)
2. [Environnement Technique](#environnement-technique)
3. [Insaller le projet en local](/.docs/INSTALL.md)
4. [Liste des environements](/.docs/ENV.md)
5. [Processus de livraison](/.docs/DEPLOY.md)
6. [Test et Intégration continue](/.docs/CI-AND-TEST.md)

# Présentation du projet

### Objectif et technologies utilisées

Réalisation du site Internet permettant d'accéder aux contenus de la BPI

 Nom | Desciption
 ---|----
 <img src="http://symfony.com//images/v5/pictos/home-main-illu.svg" width="50" /> | Symfony 3.4
  | Guzzle pour client Rest
  |  



### Documentation

Trigramme | Désciption | lien
---|---|---
 | Dossier Projet | https://sedonafr0.sharepoint.com/sites/BibliothequePublique-InterfaceCatalogue/Documents%20partages/Forms/AllItems.aspx
SFD | spécification fonctionnelle détaillée | https://sedonafr0.sharepoint.com/:w:/r/sites/BibliothequePublique-InterfaceCatalogue/_layouts/15/Doc.aspx?sourcedoc=%7B614C78A5-3AB0-415A-9777-F281A28D9BEF%7D&file=BPI%20-%20Interface%20Catalogue%20-%20Dossier%20de%20Conception%20Fonctionnelle%20-%20V2%20-%2020190312.docx&action=default&mobileredirect=true 
DAT | Dossier d’Architecture Technique | https://sedonafr0.sharepoint.com/:w:/r/sites/BibliothequePublique-InterfaceCatalogue/_layouts/15/Doc.aspx?sourcedoc=%7BD2F4E35F-28CA-41DF-86BD-D913FDD6BD35%7D&file=Bpi%20-%20InMedia%20-%20Catalogue%20V2%20-%20Infrastructure1.3.docx&action=default&mobileredirect=true
DEI | Dossier d’Exploitation Informatique | A faire 
IUP | Pocedure de déploiement | A faire 

### spécificités du projet

Intégration in first
Exigences sur l'accessibilité (niveau 3)

# Environnement Technique

liste des dépendances nécessaires au bon fonctionnement de l'appli :

* Git 1.7.2.3 ou supérieure (http://www.git-scm.com/downloads)
* PHP 7.4 
* Postgre 9.6
* Apache 2.4

### INSTALLATION

## Requirements

# PHP

* curl -sS https://getcomposer.org/installer > composer.php
* yum install epel-release yum-utils
* yum install http://rpms.remirepo.net/enterprise/remi-release-7.rpm
* yum-config-manager --enable remi-php74
* php -v
* yum install php php-common php-opcache php-mcrypt php-cli php-gd php-curl php-mysqlnd php-xml php-ldap php-mbstring

# NodeJS

* curl -sL https://rpm.nodesource.com/setup_12.x > nodejs_12.x_setup
* yum remove -y nodejs npm
* (cat nodejs_12.x_setup ) | bash -

# Yarn

* wget https://dl.yarnpkg.com/rpm/yarn.repo
* mv yarn.repo /etc/yum.repos.d/
* yum install yarn

## Build

* #a voir selon les codes d'erreur #(rm composer.lock)
* composer install
* yarn install
* yarn encore production
* make package

* composer dump-autoload
* composer install && yarn install && yarn encore production && make package
