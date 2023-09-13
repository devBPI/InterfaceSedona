Processus de livraison
=============

# Pre requis

1. Disposer d'un compte [Github](https://github.com/)
3. [Enregister sa clé ssh sur GitHub](https://github.com/settings/ssh/new) pour pull/push sur ce dépôt
2. Avoir accès au compte de la BPI : https://github.com/devBPI
   demander un accès au dépôt si accès refusé


#  En Pré-production & Production

1. Synchroniser la branche à livrer sur github : https://github.com/devBPI/InterfaceSedona
    - figer la version sur composer.json, un tag sera automatiquement crée par la step "check-release" de la pipline
    - ajouter le remote dans la config de git
      => ```git remote add github git@github.com:devBPI/InterfaceSedona.git```
    - ```git push github```
    - ```git push github [tag]```
2. Créer un ticket pour demander à la SI de BPI de livrer :
    - aller sur l'outil de ticketing : https://git.sedona.fr/redmine/bpi/interface-catalogue/-/issues
    - créer un nouveau ticket
        - Label => Demande de livraison
        - assigné => Artur Covanov
        - message =>
          ```markdown
                  Bonjour,     
                       
                  Voici le contenu de la livraison à effectuer sur la (préprod/prod) : [redmine_roadmap_link]     
                  La branche concernée est [git_branch_name]. Un tag [git_tag_name] fige cette version.     
                  Pouvez-vous s'il vous plaît effectuer la livraison en (préprod/prod) ?     
                       
                  Je vous remercie.     
          ```
   - Une fois le lot valider,    
     [créer une nouvelle PR](https://github.com/devBPI/InterfaceSedona/pulls) de la branche actuelle vers master et assigner celle-ci a Artur Covanov



[Retour au README.md](../README.md)
