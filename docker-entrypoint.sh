# #!/bin/sh

# # Attendre que la base de données soit prête
# echo "Waiting for database to be ready..."
# while ! pg_isready -h $DB_HOST -p $DB_PORT -U $DB_USERNAME; do
#   echo "Database is unavailable - sleeping"
#   sleep 1
# done

# echo "Database is up - executing migrations"
# php artisan migrate --force

# echo "Starting Laravel application..."
# exec "$@"







#!/bin/sh

echo "Waiting for database..."
while ! pg_isready -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME"; do
  sleep 2
done

echo "Running migrations..."
php artisan migrate --force

echo "Clear and optimize caches..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Generate Swagger docs..."
php artisan l5-swagger:generate || true

echo "Create storage link..."
php artisan storage:link || true

echo "✅ App Ready!"
exec "$@"
