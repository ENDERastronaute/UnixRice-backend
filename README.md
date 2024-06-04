
Créer la base de données nix4all dans PostgreSQL.
Il faut modifier les informations dans le fichier .env par rapport à vôtre configuration de PostgreSQL. (le DB_USERNAME et DB_PASSWORD)

`composer update`  

`php artisan migrate:fresh`  

`php artisan db:seed`