Processus de livraison
=============

On décrit ici la procédure de déploiement pour les différents environnements du projet.

#  Sur rancheur dev

1. Merger les modifications sur `master`
2. S'assurer que le _pipeline_ est passé sans problème
3. Lancer l'action manuelle `deploy:rancher_dev` (menu _manual actions_ en fin de ligne dans la vue `CI / CD > Pipelines`)

Si la tache de déploiement peut être bloquée si le _pipeline_ a été exécuté depuis trop longtemps (expiration du cache d'artifact).  
Dans ce cas :
 * Soit relancer le _pipeline_ pour regénérer un artifact à déployer,
 * Soit utiliser la commande `Re-deploy` dans la section `CI / CD > Environments`

![](DEPLOY_int.png)



#  En production

TODO : a définir

[Retour au README.md](../../../../README.md)
