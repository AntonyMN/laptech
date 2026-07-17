#!/usr/bin/env bash
# =============================================================================
# Laptech — daily off-server database backup
# =============================================================================
# Runs on the LOCAL machine (via cron). It SSHes to the temporary server,
# runs mysqldump there, and streams the gzipped dump straight back to a local
# file — one step, nothing left behind on the server — then rotates old copies.
#
# Because the server is temporary/quasi-live, these local dumps are the
# authoritative record and the basis for the eventual migration to a permanent
# home (restore with:  gunzip -c FILE.sql.gz | mysql -u <user> -p ).
#
# Secrets (SSH + DB passwords) are read from a 0600 file OUTSIDE this repo:
#   ~/.config/laptech/backup.env   (SSHPASS=... , DB_PASS=...)
# If that file is absent, the script assumes SSH-key auth + a server ~/.my.cnf.
# =============================================================================

set -euo pipefail

# ---------------------------- configuration ----------------------------------
SERVER="ubuntu@3.126.147.92"
SSH_PORT="22"
REMOTE_DB="laptech"
LOCAL_DIR="${LAPTECH_BACKUP_DIR:-$HOME/laptech-backups}"
RETENTION_DAYS="${LAPTECH_RETENTION_DAYS:-14}"
SECRET_FILE="${LAPTECH_SECRET_FILE:-$HOME/.config/laptech/backup.env}"
# -----------------------------------------------------------------------------

log() { printf '%s  %s\n' "$(date '+%F %T')" "$*"; }

# Load secrets if present (SSHPASS, DB_PASS)
if [[ -f "$SECRET_FILE" ]]; then
  # shellcheck disable=SC1090
  source "$SECRET_FILE"
fi

mkdir -p "$LOCAL_DIR"
STAMP="$(date '+%Y-%m-%d_%H%M%S')"
OUT="$LOCAL_DIR/laptech_${STAMP}.sql.gz"

# SSH transport: password (sshpass) if provided, else key auth
if [[ -n "${SSHPASS:-}" ]] && command -v sshpass >/dev/null; then
  export SSHPASS
  SSH=(sshpass -e ssh -p "$SSH_PORT" -o StrictHostKeyChecking=accept-new -o UserKnownHostsFile="$HOME/.ssh/known_hosts")
else
  SSH=(ssh -p "$SSH_PORT")
fi

# Remote dump command. --databases includes CREATE DATABASE so a restore is
# self-contained. Password comes from DB_PASS (secret file), never hard-coded.
if [[ -n "${DB_PASS:-}" ]]; then
  REMOTE_CMD="mysqldump -u sa -p'${DB_PASS}' --single-transaction --routines --triggers --no-tablespaces --databases ${REMOTE_DB} | gzip -c"
else
  REMOTE_CMD="mysqldump --single-transaction --routines --triggers --no-tablespaces --databases ${REMOTE_DB} | gzip -c"
fi

log "Backing up '${REMOTE_DB}' from ${SERVER} -> ${OUT}"

if "${SSH[@]}" "$SERVER" "$REMOTE_CMD" > "$OUT"; then
  # Integrity: gzip must be valid and file non-trivial (an empty/failed dump is tiny)
  if gzip -t "$OUT" 2>/dev/null && [[ "$(stat -c%s "$OUT")" -gt 500 ]]; then
    log "OK: saved $(du -h "$OUT" | cut -f1)"
  else
    log "ERROR: dump empty/corrupt — removing ${OUT}"
    rm -f "$OUT"
    exit 1
  fi
else
  log "ERROR: SSH/mysqldump failed"
  rm -f "$OUT"
  exit 1
fi

# Rotate: drop local dumps older than RETENTION_DAYS
find "$LOCAL_DIR" -maxdepth 1 -name 'laptech_*.sql.gz' -type f -mtime +"$RETENTION_DAYS" -delete
log "Rotation done (kept ~${RETENTION_DAYS} days). Local backups: $(ls -1 "$LOCAL_DIR"/laptech_*.sql.gz 2>/dev/null | wc -l)"
