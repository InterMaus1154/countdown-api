#!/usr/bin/env bash
#
# Deploy script for the Countdown API (Laravel) on a Hetzner webhost.
# Run from the project root on the server:  ./deploy.sh
#
# Override binaries if the defaults are not right for your host, e.g.:
#   PHP=php8.3 COMPOSER="php8.3 /usr/local/bin/composer" ./deploy.sh

set -euo pipefail

BRANCH="${BRANCH:-master}"
PHP="${PHP:-php}"
COMPOSER="${COMPOSER:-composer}"

cd "$(dirname "${BASH_SOURCE[0]}")"

echo "==> Entering maintenance mode"
$PHP artisan down --retry=60 || true

# Always bring the app back up, even if a step below fails.
trap '$PHP artisan up' EXIT

echo "==> Pulling latest from origin/$BRANCH"
git fetch origin "$BRANCH"
git checkout "$BRANCH"
git pull --ff-only origin "$BRANCH"

echo "==> Installing Composer dependencies"
$COMPOSER install --no-dev --prefer-dist --optimize-autoloader --no-interaction

echo "==> Running migrations"
$PHP artisan migrate --force

echo "==> Clearing caches"
$PHP artisan optimize:clear

echo "==> Rebuilding caches"
$PHP artisan config:cache
$PHP artisan route:cache
$PHP artisan event:cache
$PHP artisan view:cache

echo "==> Deploy complete"
