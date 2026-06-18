#!/usr/bin/env sh
set -eu

docker compose exec -T app php scripts/seed-database.php
