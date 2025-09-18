<?php
// Load environment variables
$host = getenv('DB_HOST') ?: 'shinkansen.proxy.rlwy.net';
$db   = getenv('DB_NAME') ?: 'railway';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'CLLEalwgSpDVGxCEjnUNwKbonlxvEBNy';
$port = getenv('DB_PORT') ?: 26593;

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optional: set default fetch mode
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // Optional: set charset
    $pdo->exec("SET NAMES 'utf8mb4'");
} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}
