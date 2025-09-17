<?php
class DB {
    public static function connect() {
        $host = 'containers-us-west-123.railway.app';
        $db   = 'railway';
        $user = 'root';
        $pass = 'your_password_here';
        $port = 1234; // Replace with your actual Railway port

        try {
            $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully\n";
            return $pdo;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}
