# Comment tester son application ?

A faire


# Intégration continue

Nom| Url | Désciption| Etape CI | Accée
---|---|---|---|---
Sonar| https://sonar.cicd.sedona.fr/dashboard?id=com.saintgobain%3Ae-salon-back-office | Test de Quartiter de code | quality:sonar | LDAP
insight | https://insight.symfony.com/projects/5574329e-b59f-4e10-a071-9a8ae7169614 | Test de Quartiter de code | quality:insight | Comptes symfony
GitLab CI | https://git.sedona.fr/saintgobain/esalon/e-salon-back-office/pipelines  | Générations des artefactes|  | LDAP
Nexus |  https://nexus.cicd.sedona.fr/#browse/welcome | Archivage des artefactes| build:package +   publish:package  | LDAP
Rancher | http://rancher.sedona.fr:8080/env/1a3061/apps/stacks/1st1251 | Déploiement des package en dev | deploy:rancher_dev | LDAP
AWX | https://awx.cicd.sedona.fr | Déploiement des package en preprod | N/A | LDAP


[Retour au README.md](../../../../README.md)
