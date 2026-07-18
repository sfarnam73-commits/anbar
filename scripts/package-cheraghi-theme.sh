#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
THEME_DIR="$ROOT_DIR/cheraghi-hello-child"
DIST_DIR="$ROOT_DIR/dist"
ZIP_FILE="$DIST_DIR/cheraghi-hello-child.zip"

if [[ ! -f "$THEME_DIR/style.css" || ! -f "$THEME_DIR/functions.php" ]]; then
  echo "Theme folder is incomplete: $THEME_DIR" >&2
  exit 1
fi

mkdir -p "$DIST_DIR"
rm -f "$ZIP_FILE"

(
  cd "$ROOT_DIR"
  zip -r "$ZIP_FILE" cheraghi-hello-child \
    -x '*/.DS_Store' \
    -x '*/node_modules/*' \
    -x '*/vendor/*'
)

echo "Created: $ZIP_FILE"
