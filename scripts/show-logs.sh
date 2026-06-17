#!/usr/bin/env sh
set -eu

LOG_DIR="${LOG_DIR:-sys/logs}"
LOG_FILE="${1:-$LOG_DIR/app-$(date +%Y-%m-%d).log}"

if [ ! -f "$LOG_FILE" ]; then
  echo "No log file found at $LOG_FILE"
  exit 0
fi

tail -n "${LINES:-80}" "$LOG_FILE"
