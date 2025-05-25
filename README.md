INSTALLATION

# Installer les dépendances listées (sans les mettre à jour)

composer install
npm install

# Ajouter manuellement les dépendances nécessaires

composer require intervention/image
composer require laravel-lang/lang --dev
npm install marked

composer require fakerphp/faker --dev

<!-- Si besoin -->

composer update league/commonmark

# Configuration habituelle Laravel

cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate --seed

# Lancer le serveur frontend

npm run dev

## 🛠️ Astuce Git – Fichier qui n'apparaît pas après un `git pull`

Si un fichier versionné (comme `favicon.ico`) n’apparaît pas après un `git pull`, même s’il est bien présent dans le dépôt GitHub :

1. Forcer le pull avec rebase :
   git pull --rebase

# Vérifier que le fichier est bien suivi :

git ls-files storage/app/public/images

# Si nécessaire, restaurer manuellement :

git restore storage/app/public/images/favicon.ico
