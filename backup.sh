#!/bin/bash
set -e

MYSQL_USER="root"
MYSQL_PASS="secret"
MYSQL_DB="laravel"
COMPOSE_DIR="$(cd "$(dirname "$0")/src" && pwd)"
BACKUP_DIR="$(dirname "$0")"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="$BACKUP_DIR/backup_${MYSQL_DB}_${TIMESTAMP}.sql.gz"

compose() {
    docker compose -f "$COMPOSE_DIR/../docker-compose.yml" exec -T db "$@"
}

case "${1:-backup}" in
    backup)
        echo "Backing up database '$MYSQL_DB'..."
        compose mysqldump -u "$MYSQL_USER" -p"$MYSQL_PASS" "$MYSQL_DB" --no-tablespaces | gzip > "$BACKUP_FILE"
        echo "Done: $(wc -c < "$BACKUP_FILE") bytes -> $(basename "$BACKUP_FILE")"
        ;;
    restore)
        FILE="$2"
        if [ -z "$FILE" ]; then
            FILE=$(ls -t "$BACKUP_DIR"/backup_${MYSQL_DB}_*.sql.gz 2>/dev/null | head -1)
        fi
        if [ -z "$FILE" ] || [ ! -f "$FILE" ]; then
            echo "Usage: $0 restore [file.sql.gz]"
            exit 1
        fi
        echo "Restoring from '$FILE'..."
        gunzip -c "$FILE" | compose mysql -u "$MYSQL_USER" -p"$MYSQL_PASS" "$MYSQL_DB"
        echo "Restore complete."
        ;;
    *)
        echo "Usage: $0 {backup|restore [file.sql.gz]}"
        exit 1
        ;;
esac
