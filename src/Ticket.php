<?php
require_once __DIR__ . '/../config/db.php';

class Ticket {
    public static function create($name, $email, $method, $code, $qr_path) {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("INSERT INTO tickets (name, email, payment_method, code, qr_path) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $method, $code, $qr_path]);
    }
}
