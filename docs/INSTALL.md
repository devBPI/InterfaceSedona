Insaller le projet sur votre poste
=============

Ce projet est dockeriser.

Cloner le projet dans votre répertoire de travail (emplacement recommandé : `c:\docker`)  
Lancer powershell, et dans celui-ci lancez les commandes ci-dessous :


## 1)  Installer Docker
 * Windows :  voir avec l'infra 
 * Linux :  https://redmine.sedona.fr/projects/php/wiki

  Et lancer la commande
```
$ docker login registry.sedona.fr
```

## 2) installer [portainer.io](https://portainer.io/) et [traefik.io](https://traefik.io/)

* Sur OpenSusue installer le [PlayBook](https://git.sedona.fr/sedona/systemd-webdev-services)
* Sur windows .... changer d'OS 



##  3) Télécharger le projet en local 
et lancer les commandes suivantes dans le dossier d'installation
```
    $ git config --global core.autocrlf input
    $ git clone git@git.sedona.fr:bpi/catalogue/bpi-catalogue.git

    $ cd bpi-catalogue
    $ docker-compose up
```

![](portainer.png)

##  4)  ajouter les URLs du projet au fichier de hosts 

(/etc/hosts)
```
127.0.0.1    e-salon.saintgobain.docker
```


[Retour au README.md](../README.md) 
