CI et Test
=============

# Comment tester son application ?

A faire


# Intégration continue

Nom| Url | Désciption| Etape CI | Accée
---|---|---|---|---
Sonar| https://sonar.cicd.sedona.fr/dashboard?id=com.bpi%3Abpi-catalogue | Test de Quartiter de code | quality:sonar | LDAP
insight | https://insight.symfony.com/projects/d00b38e4-085a-4648-b87f-86de05d98aab | Test de Quartiter de code | quality:insight | Comptes symfony
GitLab CI | https://git.sedona.fr/bpi/catalogue/bpi-catalogue/pipelines  | Générations des artefactes|  | LDAP
Artifactory |  https://repo.cicd.sedona.fr/artifactory/webapp/#/artifacts/browse/tree/General/php-release/fr/bpi/catalogue/catalogue-site | Archivage des artefactes| build:package +   publish:package  | LDAP
Rancher | https://rancher.sedona.fr/env/1a3061/apps/stacks/1st1366 | Déploiement des package en dev | deploy:rancher_dev | LDAP
AWX | https://awx.cicd.sedona.fr | Déploiement des package en preprod | N/A | LDAP


[Retour au README.md](../README.md)
