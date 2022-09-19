# PizzApp
Application Mobile PizzApp (React-Ionic / Symfony)

Il s'agit d'un projet réalisé en groupe de 3 personnes pour la validation du titre pro Concepteur/Développeur d'Applications (CDA) au sein de l'école RI7.
Le but était de mettre en place une application de livraison de pizzas en React (front) et Symfony (back).

Nous avons choisi d'utiliser Ionic pour le front, qui fonctionne avec React et permet de créer directement des composants prévus pour iOS/Android grâce à Capacitor.

J'ai travaillé sur la partie front-end du site, où le client peut se connecter (Login), s'inscrire en base de données s'il ne dispose pas de compte (Register), 
et une fois connecté, il arrive sur la page Home où l'adresse qu'il a renseignée est alors convertie en coordonnées GPS (longitude, latitude) via l'API Adresses du Gouvernement (BAN).
Un algorithme de calcul de distances entre alors en jeu (findNearby) et compare la distance de l'utilisateur avec chaque point de vente, puis retourne toutes celles > 15km.

Le client voit alors s'afficher sur la page Home les 2 pizzerias les plus proches où il peut passer commande.
Il a la possibilité d'accéder à "Mon Compte" pour modifier ses informations ou bien voir la liste de ses commandes, passées ou en cours.

L'application pourrait bien sûr être améliorée (notamment au niveau du style CSS), mais elle est déjà fonctionnelle et c'est ce que nous attendions pour le titre.

L'application fonctionne en faisant tourner le Back-end en Symfony, qui expose les données récupérables via API Platform, que le front interroge via des requêtes HTTP avec la librairie Axios.

Au niveau du déploiement, il faut donc :
  - installer PHP 8.1, 
  - Composer, 
  - MySQL 5.7, 
  - PHPMyAdmin,
  - Symfony,
  - OPENSSL pour générer un faux certificat SSL afin de faire tourner le local en https.
  
Il faut installer également le Webpack Encore ("npm install @symfony/webpack-encore --save-dev"), 
Sass Loader ("npm install sass-loader@^12.0.0 sass"), 
créer un fichier .env.local pour y renseigner la base de données (DATABASE_URL="mysql://user:password@127.0.0.1:3306/database_name?serverVersion=5.7.24&charset=utf8mb4")

Il faut générer les clés qui vont permettre de mettre en place les tokens JWT, qui se trouvent dans des fichiers Lexik/Jwt et qui n’ont pas été transmis par Git à cause du .gitignore : 
composer require lexik/jwt-authentication-bundle
mkdir -p config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096  
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem 

!! Bien noter la clé secrète qui vous sera demandée pour la création (dans le fichier .env.local " # JWT_PASSPHRASE=votreclé " )

Et ensuite créer la database et les fixtures via Doctrine :
 - composer install
 - npm run dev
 - symfony console doctrine:database:create
Effacer les migrations déjà existantes dans le dossier Symfony, puis :
 - symfony console make:migration
 - symfony console doctrine:migrations:migrate
 - symfony console doctrine:fixtures:load 
 - symfony server:ca:install
 
Puis lancer le serveur Symfony : 
 - symfony server:start

Au niveau du déploiement de l'application front client en React-Ionic, il faut juste lancer npm/yarn install pour générer les dépendances, installer Ionic s'il n'est pas déjà présent via "npm install -g @ionic/cli",
puis "ionic serve" pour lancer le serveur.

En résumé c'est une application complète qui nous a donné beaucoup de travail, car nous avons abordé pour la première fois des notions que nous ne connaissions pas, et avec lesquelles nous nous sommes familiarisés.
La première mise en place est longue mais une fois que tout est installé, cela va beaucoup plus vite !
Au niveau des données de tests, la connexion à l'application est possible avec n'importe quel utilisateur (sauf l'admin) et le mot de passe "secret".

Bonne lecture et bonne visite :)
