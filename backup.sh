#!/bin/bash

# === CONFIGURACION ===
DB_USER="u991303450_mario_pos"
DB_PASS="Rojas*250007"
DB_NAME="u991303450_multipos"

# Carpeta backups (ya existe)
BACKUP_DIR="$(pwd)/backups"

# Fecha legible humana
FECHA=$(date +%Y-%m-%d_%H-%M)

echo "Iniciando backup..."

mysqldump -u $DB_USER -p"$DB_PASS" $DB_NAME > $BACKUP_DIR/backup_multipos_$FECHA.sql

echo "Backup terminado:"
echo "$BACKUP_DIR/backup_multipos_$FECHA.sql"
