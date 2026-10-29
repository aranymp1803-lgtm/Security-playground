#!/usr/bin/env bash
# Run from the Attacker/Client VM.
# These tests send requests only to the lab web server.

BASE_URL="http://10.61.0.247/"

echo "=== Correct credentials: expect Access granted! ==="
curl -s -X POST \
  --data-urlencode "username=student" \
  --data-urlencode "password=test123" \
  "$BASE_URL" | grep -oE "Access granted!|Access denied!"

echo
echo "=== Incorrect password: expect Access denied! ==="
curl -s -X POST \
  --data-urlencode "username=student" \
  --data-urlencode "password=wrongpassword" \
  "$BASE_URL" | grep -oE "Access granted!|Access denied!"

echo
echo "=== SQL injection regression test: fixed app should deny access ==="
curl -s -X POST \
  --data-urlencode "username=student' OR '1'='1' -- " \
  --data-urlencode "password=wrongpassword" \
  "$BASE_URL" | grep -oE "Access granted!|Access denied!"

echo
echo "=== HTTP response headers ==="
curl -I "$BASE_URL"

echo
echo "=== Web app HTML ==="
curl -s "$BASE_URL"
