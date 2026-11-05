<?php
// Central configuration. Values come from environment variables so no
// credentials are committed to the repository. See .env.example.

function env_or(string $key, string $default): string {
    $v = getenv($key);
    return ($v === false || $v === '') ? $default : $v;
}

define('DB_HOST', env_or('DB_HOST', 'localhost'));
define('DB_PORT', (int) env_or('DB_PORT', '3306'));
define('DB_NAME', env_or('DB_NAME', 'nestpoint'));
define('DB_USER', env_or('DB_USER', 'nestpoint'));
define('DB_PASS', env_or('DB_PASS', 'nestpoint'));

function db_mysqli(): mysqli {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    $conn->set_charset('utf8mb4');
    return $conn;
}

function db_pdo(): PDO {
    return new PDO(
        'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
}

/** Redirect to the right login page unless the visitor is signed in with one of $roles. */
function require_role(string ...$roles): void {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (!in_array($_SESSION['user_type'] ?? '', $roles, true)) {
        $login = ['buyer' => 'loginb.php', 'seller' => 'logins.php', 'admin' => 'login.php'][$roles[0]] ?? 'global_login.php';
        header('Location: ' . $login);
        exit;
    }
}
