<?php
// Shared connection for scripts that `require("db.php")`.
require_once __DIR__ . '/config.php';
$conn = db_mysqli();
