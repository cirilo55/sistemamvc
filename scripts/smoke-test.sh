#!/usr/bin/env sh
set -eu

BASE_URL="${BASE_URL:-http://localhost:8080}"
COOKIE_FILE="${COOKIE_FILE:-/tmp/sistemamvc-cookies.txt}"

sh scripts/seed-database.sh

curl -fsS -o /dev/null "$BASE_URL/login"
curl -fsS -c "$COOKIE_FILE" -d 'username=Admin&password=1234' -X POST "$BASE_URL/login" -o /dev/null

for path in / /users /tarefas /clientes /config /myprofile; do
  curl -fsS -b "$COOKIE_FILE" -o /dev/null "$BASE_URL$path"
  echo "OK $path"
done

rm -f "$COOKIE_FILE"
