#!/bin/bash
set -e

APP_DIR="/app/eBot-CSGO-Web"
SOURCE_DIR="/app/source"

# Copy source to volume target if not already present
if [ ! -f "$APP_DIR/symfony" ]; then
    echo "[entrypoint] Copying source files to $APP_DIR..."
    cp -r "$SOURCE_DIR/." "$APP_DIR/"
fi

cd "$APP_DIR"

# Remove installation wizard (safe on every start)
rm -rf web/installation

# Wait for MySQL to be ready
MYSQL_HOST="${MYSQL_HOST:-mysqldb}"
echo "[entrypoint] Waiting for MySQL at $MYSQL_HOST..."
until mysql -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE" -e "SELECT 1" > /dev/null 2>&1; do
    echo "[entrypoint] MySQL not ready yet, retrying in 3s..."
    sleep 3
done
echo "[entrypoint] MySQL is ready."

# Check if schema already exists by probing sf_guard_user table
TABLE_EXISTS=$(mysql -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE" \
    -e "SHOW TABLES LIKE 'sf_guard_user';" 2>/dev/null | grep -c "sf_guard_user" || true)

if [ "$TABLE_EXISTS" -eq 0 ]; then
    echo "[entrypoint] Schema not found — building database..."
    php symfony doctrine:build --all --no-confirmation

    echo "[entrypoint] Creating admin user..."
    php symfony guard:create-user --is-super-admin \
        "$EBOT_ADMIN_EMAIL" \
        "$EBOT_ADMIN_LOGIN" \
        "$EBOT_ADMIN_PASSWORD" \
        "${EBOT_ADMIN_FIRSTNAME:-}" \
        "${EBOT_ADMIN_LASTNAME:-}"
else
    echo "[entrypoint] Schema already exists — skipping doctrine:build."
fi

# Clear Symfony cache (always safe)
echo "[entrypoint] Clearing Symfony cache..."
php symfony cc

echo "[entrypoint] Starting php-fpm..."
exec php-fpm
