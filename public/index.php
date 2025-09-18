<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/QRGenerator.php';
require_once __DIR__ . '/../src/Mailer.php';

use App\QRGenerator;
use App\Mailer;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? 'Guest');
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $payment = htmlspecialchars($_POST['payment'] ?? 'Unknown');

    if (!$email) {
        echo "Invalid email address.";
        exit;
    }

    // 🎟️ Generate numeric ticket code
    $ticketCode = rand(100000, 999999); // Or use auto-increment ID later

    // 🎯 Generate QR code
    $qrPath = QRGenerator::generate((string)$ticketCode);

    // 💾 Save to database
    $stmt = $pdo->prepare("INSERT INTO tickets (name, email, payment_method, ticket_code, qr_code, payment_status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $name,
        $email,
        $payment,
        $ticketCode,
        basename($qrPath),
        'Paid'
    ]);

    // 📧 Send email
    Mailer::sendTicket($email, $name, $ticketCode, $qrPath);

    // 🖼️ Output
    echo "<h2>✅ Ticket Generated</h2>";
    echo "<p><strong>Name:</strong> $name</p>";
    echo "<p><strong>Email:</strong> $email</p>";
    echo "<p><strong>Payment Method:</strong> $payment</p>";
    echo "<p><strong>Ticket Code:</strong> $ticketCode</p>";
    echo "<img src='qrcodes/" . basename($qrPath) . "' alt='QR Code'>";
} else {
    // 📝 Form UI
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
