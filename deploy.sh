#!/bin/bash

# ==============================================================================
# Laptech — Deployment Script
# ==============================================================================
# Server:  ubuntu@3.126.147.92 (AWS Lightsail, Ubuntu 24.04) — SHARED host
# Path:    /var/www/laptech
# Domain:  laptech.co.ke (+ www)  — Nginx + PHP 8.3-FPM + MySQL 8.0
# Type:    QUASI-LIVE / temporary. Real client data lives on the server and is
#          backed up daily to the local machine (see backup-laptech.sh).
#
# ⚠️  DATA-PRESERVING: this deploy runs `migrate --force` only. It NEVER seeds or
#     runs migrate:fresh — that would destroy the client's live data. Initial
#     data was imported from localhost via mysqldump, not seeded.
#
# Assets are built LOCALLY and rsynced. The server .env is the source of truth
# and is never synced.
#
# Auth: export SSHPASS='<password>' for password auth (needs sshpass), else keys.
# ==============================================================================

set -e

SERVER_HOST="3.126.147.92"
SERVER_USER="ubuntu"
SERVER_PORT="22"
DEPLOY_PATH="/var/www/laptech"

SECRET_FILE="$HOME/.config/laptech/backup.env"
if [ -f "$SECRET_FILE" ]; then
  source "$SECRET_FILE"
  export SSHPASS
fi

if [ -n "$SSHPASS" ] && command -v sshpass >/dev/null; then
  SSH_WRAP="sshpass -e ssh -p $SERVER_PORT -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null"
else
  SSH_WRAP="ssh -p $SERVER_PORT"
fi

cd "$(dirname "$0")"

echo "=== Deploying Laptech -> $SERVER_USER@$SERVER_HOST:$DEPLOY_PATH ==="
echo ">>> Taking a safety backup BEFORE deploying (protects live data)..."
[ -x ./backup-laptech.sh ] && ./backup-laptech.sh || echo "  (backup-laptech.sh not run — continuing)"

echo "1. Building frontend assets locally..."
npm install --no-audit --no-fund
npm run build

echo "2. Syncing code (excludes ALL .env* — server .env is source of truth)..."
rsync -az --delete -e "$SSH_WRAP" \
    --exclude 'node_modules' --exclude 'vendor' --exclude '.git' \
    --exclude '.env*' --exclude 'public/hot' --exclude 'public/storage' \
    --exclude 'storage/*.key' --exclude 'storage/logs/*' \
    --exclude 'bootstrap/cache/*' --exclude 'storage/framework/cache/*' \
    --exclude 'storage/framework/sessions/*' --exclude 'storage/framework/views/*' \
    ./ "$SERVER_USER@$SERVER_HOST:$DEPLOY_PATH/" || [ $? -eq 23 ]
# NOTE: bootstrap/cache/* and storage/framework/* are server-owned runtime dirs.
# Syncing them from a dev machine overwrites the server's --no-dev package cache
# (dev-only providers → "class not found") and breaks www-data write perms
# (tempnam() 500s). The remote step below re-runs composer + chown/chmod to heal
# state, but never sync these directories.

echo "3. Remote post-deploy (NON-destructive: migrate only, never seed/fresh)..."
$SSH_WRAP "$SERVER_USER@$SERVER_HOST" 'bash -s' <<'EOF'
    set -e
    cd /var/www/laptech
    rm -f public/hot
    export COMPOSER_MEMORY_LIMIT=-1
    composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-interaction
    php artisan migrate --force
    php artisan storage:link || true
    php artisan optimize:clear
    php artisan optimize
    sudo chown -R ubuntu:www-data /var/www/laptech
    sudo chmod -R ug+rwx /var/www/laptech/storage /var/www/laptech/bootstrap/cache
    sudo systemctl reload php8.3-fpm
EOF

echo "=== Done: https://laptech.co.ke ==="
