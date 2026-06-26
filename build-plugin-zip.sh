#!/usr/bin/env zsh
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")" && pwd)"
PLUGIN_SLUG="sd-pohorje-accounts"
PLUGIN_DIR="$ROOT_DIR/$PLUGIN_SLUG"
PLUGIN_MAIN_FILE="$PLUGIN_DIR/${PLUGIN_SLUG}.php"
OUTPUT_ZIP="$ROOT_DIR/${PLUGIN_SLUG}.zip"

if [[ ! -d "$PLUGIN_DIR" ]]; then
  echo "Plugin directory not found: $PLUGIN_DIR" >&2
  exit 1
fi

if [[ ! -f "$PLUGIN_MAIN_FILE" ]]; then
  echo "Plugin main file not found: $PLUGIN_MAIN_FILE" >&2
  exit 1
fi

CURRENT_VERSION="$(grep -E '^ \* Version:' "$PLUGIN_MAIN_FILE" | head -n1 | sed -E 's/^ \* Version:[[:space:]]*//')"

if [[ -z "$CURRENT_VERSION" ]]; then
  echo "Could not read current plugin version from: $PLUGIN_MAIN_FILE" >&2
  exit 1
fi

if [[ ! "$CURRENT_VERSION" =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
  echo "Version format must be semver-like x.y.z, got: $CURRENT_VERSION" >&2
  exit 1
fi

IFS='.' read -r major minor patch <<< "$CURRENT_VERSION"
NEW_VERSION="${major}.${minor}.$((patch + 1))"

TMP_FILE="$(mktemp)"

if ! awk -v ver="$NEW_VERSION" '
BEGIN { header = 0; constant = 0 }
{
  if ($0 ~ /^[[:space:]]*\* Version:[[:space:]]*/) {
    print " * Version: " ver;
    header++;
    next;
  }

  if ($0 ~ /define\('\''SDP_ACCOUNTS_VERSION'\'',/) {
    print "define('\''SDP_ACCOUNTS_VERSION'\'', '\''" ver "'\'');";
    constant++;
    next;
  }

  print;
}
END {
  if (header != 1 || constant != 1) {
    exit 42;
  }
}
' "$PLUGIN_MAIN_FILE" > "$TMP_FILE"; then
  rm -f "$TMP_FILE"
  echo "Failed to update version in plugin file: $PLUGIN_MAIN_FILE" >&2
  exit 1
fi

mv "$TMP_FILE" "$PLUGIN_MAIN_FILE"

echo "Version bumped: $CURRENT_VERSION -> $NEW_VERSION"

# Build a clean zip for WordPress plugin upload.
rm -f "$OUTPUT_ZIP"
(
  cd "$ROOT_DIR"
  zip -r "$OUTPUT_ZIP" "$PLUGIN_SLUG" \
    -x "*/.DS_Store" \
    -x "*/__MACOSX/*" \
    -x "*/.git/*" \
    -x "*/.gitignore"
)

echo "Built: $OUTPUT_ZIP"
