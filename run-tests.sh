#!/bin/bash
set -euo pipefail

# Run all test cases in tests/
# Requires server to be running

URL_PREFIX='http://localhost:8080/index.php'
CASES=$(find tests/ -type f -name '*.case')

for CASE in $CASES
do
	./run-single-test.sh "$URL_PREFIX" "$CASE"
done

echo # newline
echo "All tests passed!"
