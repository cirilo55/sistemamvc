#!/usr/bin/env sh
set -eu

find . \
  \( -path './vendor' -o -path './.git' -o -path './storage/cache' \) -prune \
  -o \( -name '*.php' -o -name '*.phtml' \) -print \
  | xargs -r -n1 php -l
