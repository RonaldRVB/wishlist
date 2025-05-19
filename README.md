INSTALLATION

# Installer les dépendances listées (sans les mettre à jour)

composer install
npm install

# Ajouter manuellement les dépendances nécessaires

composer require intervention/image
composer require laravel-lang/lang --dev
npm install marked

# Configuration habituelle Laravel

cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate --seed

# Lancer le serveur frontend

npm run dev
