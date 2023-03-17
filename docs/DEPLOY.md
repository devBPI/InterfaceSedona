Processus de livraison
=============

#  En Pré-production & Production

1. Synchroniser la branche à livrer sur github : https://github.com/devBPI/InterfaceSedona
    - figer la version sur composer.json et créer un tag sur git.sedona.fr
    - créer un compte github
    - demander un accès au dépôt si accès refusé
    - installer sa clé ssh pour pull/push sur ce dépôt
    - ajouter le remote dans la config de git 
      => ```git remote add github git@github.com:devBPI/InterfaceSedona.git```
    - ```git push github```
    - ```git push github [tag]```
2. Créer un ticket redmine pour demander à la SI de BPI de livrer : 
   - aller sur redmine : https://redmine.sedona.fr/projects/retours-elements-techniques-sedona/issues
   - créer un nouveau ticket
     - tracker => Tâche
     - assigné => Artur Covanov
     - message =>
```
Bonjour,

Voici le contenu de la livraison à effectuer sur la préprod/prod : [redmine_roadmap_link]
La branche concernée est [git_branch_name]. Un tag [git_tag_name] fige cette version.
Pouvez-vous s'il vous plaît effectuer la livraison en préprod/prod ?

Je vous remercie.
```
       - valider le ticket


[Retour au README.md](../README.md)
