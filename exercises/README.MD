
## Exercice GameDetail

- Image du jeu
- Nom du jeu
- Le prix du jeu
- Les langues disponibles du jeu
- Les genres du jeu
- La description du jeu
- Les 10 derniers commentaires sur le jeu avec le nombre de vote positif et négatif qu'ils ont reçu
  (Un peu de FontAwesome SVP)
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
