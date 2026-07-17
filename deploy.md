# Deployment Details for Laptech

## Server Information
- **Domain**: laptech.co.ke (+ www) — **route the A record → 3.126.147.92** (Route 53 / registrar)
- **Provider**: AWS Lightsail (Ubuntu 24.04 LTS, 2 vCPU / 2 GB) — **SHARED** host
- **IP Address**: `3.126.147.92` (static)
- **SSH User**: `ubuntu`  ·  **Port**: 22
- **App Path**: `/var/www/laptech` (web root `/var/www/laptech/public`)
- **Web Server**: Nginx 1.24 — `/etc/nginx/sites-available/laptech`
- **PHP**: 8.3 / PHP-FPM 8.3 (socket `/run/php/php8.3-fpm.sock`)
- **Database**: MySQL 8.0 (shared), db `laptech`, user `sa` / `admin123`, `127.0.0.1:3306`
- **SSL**: Let's Encrypt via Certbot — **live** (laptech.co.ke + www, HTTP→HTTPS redirect, auto-renew)

> Previous server was DigitalOcean `samuel@159.89.109.15` (db `laptech_db`) —
> **decommissioned.** Current DB name is `laptech`.

## ⚠️ Quasi-live / temporary — data handling
This is a **temporary** deployment the client treats as live. **Real data lives on
the server** (initially imported from localhost via `mysqldump`, **not seeded**) and
must be preserved:
- **Daily off-server backup** runs on the local machine via `backup-laptech.sh`
  (cron `0 2 * * *`), streaming a gzipped dump to `~/laptech-backups/` (14-day
  rotation). This is the authoritative record for the eventual permanent-home move.
- Deploys are **data-preserving**: `deploy.sh` runs `migrate --force` only —
  **never** `db:seed` or `migrate:fresh`.

## Deploy
```bash
export SSHPASS='<server-password>'   # or use SSH keys
./deploy.sh          # takes a safety backup, builds locally, rsyncs, migrates, optimizes
```

## SSL
Live (issued 2026-07-17, auto-renewing). To re-issue/repair if ever needed, on the server:
```bash
sudo enable-laptech-ssl     # verifies DNS, issues cert for apex+www, adds redirect, sets secure cookies
```

## First-install state (already done)
- Server db `laptech` created + `sa` granted; **local data imported** (28 products + catalog).
- Nginx block live (HTTP); app serving; admin user present.
- Backup cron enabled + verified; SSL command staged.

## Access
- **URL**: https://laptech.co.ke (HTTP works now; HTTPS after DNS + `enable-laptech-ssl`)
- **Admin Console**: `/admin/dashboard`
- **Admin**: `admin@laptech.com` (imported from localhost; password as set locally — change post-launch)

## Restore a backup (teardown → permanent home)
```bash
gunzip -c ~/laptech-backups/laptech_YYYY-MM-DD_HHMMSS.sql.gz | mysql -u <user> -p
```
