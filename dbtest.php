<?php
$host = 'shinkansen.proxy.rlwy.net';
$db   = 'railway';
$user = 'root';
$pass = 'CLLEalwgSpDVGxCEjnUNwKbonlxvEBNy';
$port = 26593;

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connection successful!";
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage();
}
