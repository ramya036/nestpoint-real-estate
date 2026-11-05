#!/usr/bin/env bash
# End-to-end smoke test. Needs a MySQL database loaded with schema.sql and the
# DB_* environment variables set. Starts PHP's built-in server and walks the
# main flows with curl.
set -euo pipefail
cd "$(dirname "$0")/.."
PORT=${PORT:-8099}
BASE="http://127.0.0.1:$PORT"
php -S 127.0.0.1:$PORT >/tmp/nestpoint-php.log 2>&1 &
PHP_PID=$!
BEFORE=$(ls img)
cleanup() { kill $PHP_PID 2>/dev/null || true; for f in img/*; do grep -qxF "$(basename "$f")" <<<"$BEFORE" || rm -f "$f"; done; }
trap cleanup EXIT
for _ in $(seq 1 30); do curl -s "$BASE/index.php" >/dev/null && break; sleep 0.2; done

pass=0; fail=0
check() { if eval "$2"; then echo "PASS  $1"; pass=$((pass+1)); else echo "FAIL  $1"; fail=$((fail+1)); fi; }
JAR=$(mktemp)

status() { curl -s -o /dev/null -w '%{http_code}' "$@"; }
location() { curl -s -o /dev/null -w '%{redirect_url}' "$@"; }

check "home page loads" '[ "$(status $BASE/index.php)" = 200 ]'
check "buyer dashboard redirects when signed out" '[[ "$(location $BASE/buyer_dashboard.php)" == *loginb.php ]]'
check "seller dashboard redirects when signed out" '[[ "$(location $BASE/seller_dashboard.php)" == *logins.php ]]'
check "admin dashboard redirects when signed out" '[[ "$(location $BASE/admin_dashboard.php)" == *login.php ]]'
check "add-listing page redirects when signed out" '[[ "$(location $BASE/form.php)" == *logins.php ]]'

USER="buyer$RANDOM"
curl -s -o /dev/null -d "buyer_name=Test Buyer&buyer_user_id=$USER&buyer_email=t@example.com&buyer_password=secret123" "$BASE/buyer_register_submit.php"
check "password is stored as a bcrypt hash" '[[ "$(mysql -N -h"$DB_HOST" -P"${DB_PORT:-3306}" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "SELECT buyer_password FROM buyer_data WHERE buyer_user_id='"'"'$USER'"'"'")" == \$2y\$* ]]'

check "SQL injection login is rejected" '[[ "$(location -c $JAR -d "buyer_user_id_login='"'"' OR '"'"'1'"'"'='"'"'1&buyer_password_login='"'"' OR '"'"'1'"'"'='"'"'1" $BASE/buyer_login_submit.php)" != *buyer_dashboard.php ]]'
check "wrong password is rejected" '[[ "$(location -c $JAR -d "buyer_user_id_login=$USER&buyer_password_login=nope" $BASE/buyer_login_submit.php)" != *buyer_dashboard.php ]]'
check "correct password signs in" '[[ "$(location -c $JAR -d "buyer_user_id_login=$USER&buyer_password_login=secret123" $BASE/buyer_login_submit.php)" == *buyer_dashboard.php ]]'
check "signed-in buyer sees dashboard" 'curl -s -b $JAR $BASE/buyer_dashboard.php | grep -q "Welcome, Buyer"'
check "buyer cannot open seller pages" '[[ "$(location -b $JAR $BASE/form.php)" == *logins.php ]]'

SJAR=$(mktemp); SELLER="seller$RANDOM"
curl -s -o /dev/null -d "seller_name=Test Seller&seller_user_id=$SELLER&seller_email=s@example.com&seller_password=secret123" "$BASE/seller_register_submit.php"
curl -s -o /dev/null -c $SJAR -d "seller_user_id_login=$SELLER&seller_password_login=secret123" "$BASE/seller_login_submit.php"
PNG=$(mktemp --suffix=.png); printf '\x89PNG\r\n\x1a\n\x00\x00\x00\rIHDR\x00\x00\x00\x01\x00\x00\x00\x01\x08\x06\x00\x00\x00\x1f\x15\xc4\x89\x00\x00\x00\rIDATx\x9cc\xf8\xff\xff?\x00\x05\xfe\x02\xfe\xa7\x35\x81\x84\x00\x00\x00\x00IEND\xaeB`\x82' > $PNG
LISTING="Smoke Test Home $RANDOM"
curl -s -o /dev/null -b $SJAR -F "name=$LISTING" -F address="1 Test St" -F age=5 -F bed=3 -F ad=2 -F garden=Yes -F pa=Yes -F tax=250000 -F "file=@$PNG;type=image/png" "$BASE/insert_card.php"
check "seller can add a listing with an image" 'curl -s -b $SJAR $BASE/seller_dashboard.php | grep -q "$LISTING"'
EVIL=$(mktemp --suffix=.php); echo '<?php echo "pwned"; ?>' > $EVIL
check "non-image upload is refused" 'curl -s -b $SJAR -F name=Bad -F address=x -F age=1 -F bed=1 -F ad=1 -F garden=No -F pa=No -F tax=1 -F "file=@$EVIL;type=image/png" $BASE/insert_card.php | grep -q "Please upload a JPG"'
check "no PHP files were written to img/" '[ -z "$(ls img/*.php 2>/dev/null)" ]'

echo; echo "$pass passed, $fail failed"
[ "$fail" -eq 0 ]
