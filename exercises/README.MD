
## Exercice GameDetail


- Image du jeu
- Nom du jeu
- Le prix du jeu
- Les langues disponibles du jeu
- Les genres du jeu
- La description du jeu
- Les 10 derniers commentaires sur l jeu avec le nombre de vote positif et négatif qu'ils ont reçu
  (Un peu de FontAwesome SVP)e
- Jusqu'à 10 jeux qui ont le même genre que le jeu sur lequel on est

Exemple de page :

https://www.instant-gaming.com/en/9595-buy-forza-horizon-5-deluxe-edition-pc-xbox-one-xbox-series-xs-deluxe-edition-pc-xbox-one-xbox-serie-x-s-game-microsoft-store/


## Exercice Command


Réalisez une commande Symfony, permettant de générer des slugs à partir de l'attribut "name" du jeu.

Pour cela vous devez :
- Modifier votre entité Game, pour ajouter un attribut "slug" de type string, de taille 255, et nullable
- Générer la migration et exécutez la
- Votre classe de Command (celle qui étend Command), doit connaître TextService afin de pouvoir utiliser la fonction slugify qu'elle contient
- Elle doit aussi pouvoir récupérer tous les jeux depuis la base de données... Elle devra donc connaître une autre classe...
- La fonction execute de la commande doit :
- Parcourir les différents jeux
- Pour chacun d'entre eux mettre à jour son slug à partir du name, puis les persist (via l'EntityManager)
- Une fois qu'ils seront tous modifiés, les update en base de données (flush)
- Passez votre commande pour la tester


## Exercice - Account Detail


Depuis la page de tous les comptes, lorsque je clique sur l'oeil pour voir le détail du compte, je vais sur cette page (account_detail)

Je veux un récap de toutes les informations d'un Account (avec le montant de son portefeuille)

Un affichage de tous ses jeux, avec le temps en h:m de ceux-ci

Si vous vous sentez chaud... Le temps de jeu total !

On ajoutera aussi une section permettant de voir les 4 derniers commentaires postés par l'utilisateur, et un moyen (de votre choix) pour voir tous ceux qu'il a posté


## Exercice - Formulaire


Faire le formulaire de création d'un "Publisher" (éditeur de jeu)

Ce formulaire ne devra pas gérer la collection pour le moment (afin de simplifier le formulaire en question).

Vous ferez un formulaire qui sera utilisé pour ajouter un Publisher en base de données (route associée) et un moyen de le modifier

Tant qu'à faire... Autant faire un index pour afficher tous les Editeurs de jeux qui existent dans notre base de données
