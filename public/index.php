<?php

require_once __DIR__ . '/../src/QRGenerator.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? 'Guest';
    $email = $_POST['email'] ?? 'noemail@example.com';
    $payment = $_POST['payment'] ?? 'Unknown';

    $ticketCode = 'TKT_' . uniqid();
    $qrPath = QRGenerator::generate($ticketCode);

    echo "<h2>Ticket Generated</h2>";
    echo "<p>Name: $name</p>";
    echo "<p>Email: $email</p>";
    echo "<p>Payment Method: $payment</p>";
    echo "<p>Ticket Code: $ticketCode</p>";
    echo "<img src='qrcodes/" . basename($qrPath) . "' alt='QR Code'>";
} else {
    echo '<form method="POST">
        <input name="name" placeholder="Name"><br>
        <input name="email" placeholder="Email"><br>
        <select name="payment">
            <option value="SnapScan">SnapScan</option>
            <option value="Card">Card</option>
        </select><br>
        <button type="submit">Generate Ticket</button>
    </form>';
}
