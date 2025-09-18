<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/QRGenerator.php';
require_once __DIR__ . '/../src/Mailer.php';

use App\QRGenerator;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? 'Guest');
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $payment = htmlspecialchars($_POST['payment'] ?? 'Unknown');

    if (!$email) {
        echo "Invalid email address.";
        exit;
    }

    $ticketCode = 'TKT_' . uniqid();
    $qrPath = QRGenerator::generate($ticketCode);

    // Save to DB
    $stmt = $pdo->prepare("INSERT INTO tickets (name, email, payment_method, ticket_code) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $payment, $ticketCode]);

    // Send email
    Mailer::sendTicket($email, $name, $ticketCode, $qrPath);

    echo "<h2>Ticket Generated</h2>";
    echo "<p>Name: $name</p>";
    echo "<p>Email: $email</p>";
    echo "<p>Payment Method: $payment</p>";
    echo "<p>Ticket Code: $ticketCode</p>";
    echo "<img src='qrcodes/" . basename($qrPath) . "' alt='QR Code'>";
} else {
    echo '<form method="POST" class="container mt-5">
        <input name="name" class="form-control mb-2" placeholder="Name">
        <input name="email" class="form-control mb-2" placeholder="Email">
        <select name="payment" class="form-control mb-2">
            <option value="SnapScan">SnapScan</option>
            <option value="Card">Card</option>
        </select>
        <button type="submit" class="btn btn-primary">Generate Ticket</button>
    </form>';
}
