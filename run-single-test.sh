#!/bin/bash
set -euo pipefail

URL_PREFIX="$1"
CASE="$2"

URL_SUFFIX=$(head -n 1 "$CASE") # Only first line
EXPECTED=$(tail -n +2 "$CASE") # Skip first line
ACTUAL=$(curl --silent "$URL_PREFIX""$URL_SUFFIX")

if test "$EXPECTED" = "$ACTUAL"
then
	echo -n '.'
else
	echo #newline
	echo "Test case $CASE failed."
	echo "=== EXPECTED ==="
	echo "$EXPECTED"
	echo "=== ACTUAL ==="
	echo "$ACTUAL"
	exit 1
fi
