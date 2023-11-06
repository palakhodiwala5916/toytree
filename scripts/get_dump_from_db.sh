#!/bin/bash

DB_HOST=localhost
DB_USER=ci
DB_NAME=ci_the-room-menu-backend_develop

echo "Get structure: ${DB_USER}@${DB_HOST} ${DB_NAME}"
mysqldump -h $DB_HOST -u $DB_USER -p --no-data --set-gtid-purged=OFF --single-transaction --allow-keywords --hex-blob --events --routines $DB_NAME > $DB_NAME.sql

echo "Dump data: ${DB_USER}@${DB_HOST} ${DB_NAME}"
mysqldump -h $DB_HOST -u $DB_USER -p --max_allowed_packet=512M --no-create-info --set-gtid-purged=OFF --single-transaction --allow-keywords --hex-blob --events --routines \
 --ignore-table=$DB_NAME.auth_tokens \
 --ignore-table=$DB_NAME.delivery_logs \
 --ignore-table=$DB_NAME.log_email \
 --ignore-table=$DB_NAME.request_logs \
 --ignore-table=$DB_NAME.sessions $DB_NAME >> $DB_NAME.sql
