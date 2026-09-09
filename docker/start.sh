#!/bin/bash
set -euo pipefail

cd /var/www/html

PORT="${PORT:-10000}"
sed -i "s/listen 10000;/listen ${PORT};/g" /etc/nginx/http.d/default.conf
sed -i "s/listen \[::\]:10000;/listen [::]:${PORT};/g" /etc/nginx/http.d/default.conf

export APP_ENV="${APP_ENV:-production}"
export APP_DEBUG="${APP_DEBUG:-false}"
export LOG_CHANNEL="${LOG_CHANNEL:-stderr}"
export DB_CONNECTION="${DB_CONNECTION:-pgsql}"
export DB_URL="${DB_URL:-${DATABASE_URL:-}}"
export APP_URL="${APP_URL:-${RENDER_EXTERNAL_URL:-http://localhost}}"
export ASSET_URL="${ASSET_URL:-$APP_URL}"

php artisan package:discover --ansi --no-interaction
php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan view:cache --no-interaction
php artisan migrate --force --no-interaction

set +e
php -d display_errors=0 -r '
require "/var/www/html/vendor/autoload.php";
$app = require "/var/www/html/bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
exit(\App\Models\User::query()->exists() ? 0 : 2);
'
seed_status=$?
set -e

if [ "$seed_status" -eq 2 ]; then
  echo "Seeding database"
  php artisan db:seed --force --no-interaction
elif [ "$seed_status" -ne 0 ]; then
  echo "Could not check seed status (exit ${seed_status}); skipping seed"
else
  echo "Database already seeded"
fi

php-fpm -D
exec nginx -g "daemon off;"
