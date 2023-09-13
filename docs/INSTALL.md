Insaller le projet sur votre poste
=============

Ce projet est dockeriser.

Cloner le projet dans votre répertoire de travail (emplacement recommandé : `c:\docker`)  
Lancer powershell, et dans celui-ci lancez les commandes ci-dessous :


## 1)  Installer Docker
 * _Windows_ :  voir avec l'infra 
 * _Linux_ :  https://redmine.sedona.fr/projects/php/wiki
 * _Mac_ : penser a inclure dans la variable `COMPOSE_FILE` du fichier `.env` la config `.deploy/dev-mac/docker-compose.dev.yml`
 
 Definir un token depuis [gitlab](https://git.sedona.fr/-/profile/personal_access_tokens) avec comme droit : [read_registry](https://git.sedona.fr/-/profile/personal_access_tokens?scopes=read_registry,write_registry&name=docker-login)
 
 Et lancer la commande ` docker login registry.sedona.fr -u <username> -p <token> `

## 2) installer [portainer.io](https://portainer.io/) et [traefik.io](https://traefik.io/)

* Sur OpenSusue installer le [PlayBook](https://git.sedona.fr/sedona/systemd-webdev-services)
* Sur windows .... changer d'OS 


### 3) Activer le VPN 

Pour pouvoir utiliser la connexion LDAP et donc s'authentifier sur le site, il est nécessaire de lancer le VPN.   
Les données sont dans [Bitwarden](https://pass.cicd.sedona.fr/#/vault?collectionId=ef21989c-166d-4656-b930-90ff0e6eeaf4)
1. Téléchrgers le fichier de config OpenVPN : https://94.228.185.34/sslvpn_download.shtml
2. Ajouter a la fin du fichier "auth-user-pass auth.txt"
2. Crée un fichier "auth.txt" en copie depuis Bitwarden sur
   - la 1re ligne le login
   - sur la 2e, ligne le mdp
3. Lancer la connexion :  `sudo openvpn --config client.ovpn  –secret auth.txt`

**Attention** penser à lancer le VPN avant de lancer l'instance docker (app) car le LDAP utilise le VPN pour accéder au serveur de BPI.    
Sinon la connexion échouera.

##  3) Télécharger le projet en local 
et lancer les commandes suivantes dans le dossier d'installation
```
    $ git config --global core.autocrlf input
    $ git clone git@git.sedona.fr:bpi/catalogue/bpi-catalogue.git

    $ cd bpi-catalogue
    $ cp .env.dist .env
    $ docker-compose up
```

![](portainer.png)

##  4)  ajouter les URLs du projet au fichier de hosts 

(/etc/hosts)
```
127.0.0.1    catalogue.bpi.docker
```



[Retour au README.md](../README.md) 
