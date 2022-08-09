# Test_Mc

Ce repository a été créé dans le but de passer un test pour l'entreprise MaCompta.fr.
Le but de ce test était de créer une API avec le framework de mon choix (ici symfony 5), sans utiliser D'ORM.

Pour la création et la gestion des Uuid, j'ai utliser le Bundle RamseyUuid.
Pour la gestion du CORS j'ai utiliser le Bundle NielmoCORSBundle.

Grâce à cette API, on peut :
      - Voir la liste des comptes existants.
      - Créer un nouveau compte avec un couple login/mot de passe(non haché dans ce test)
      - Modifier un compte.
      - Supprimer un compte
      - Voir la liste des écritures pour un compte particulier.
      - Créer un nouveau compte.
      - Modifier un compte.
      - Supprimer un compte.
      
Dans une prochaine mise à jour, je dois également ajouter une route afin de récupérer tous les comptes avec leurs écritures, mais également le total de ces écritures( Crédit - Débit).
 
Cette API peut être consommé avec l'Ui disponible dans le repository Ui.

Ceci étant suelement ma deuxième API de développé, je suis preneur de toutes suggestions et/ou critique.
