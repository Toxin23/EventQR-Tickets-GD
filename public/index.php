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

    // 🧾 Status values
    $paymentStatus = 1;         // Numeric: 1 = Paid
    $statusLabel = 'Paid';      // Readable label

    // 💾 Insert ticket without ticket_code
    $stmt = $pdo->prepare("INSERT INTO tickets (name, email, payment_method, qr_code, payment_status, status_label) VALUES (?, ?, ?, '', ?, ?)");
    $stmt->execute([$name, $email, $payment, $paymentStatus, $statusLabel]);

    // 🔄 Get auto-incremented ID
    $ticketId = $pdo->lastInsertId();

    // 🎯 Generate QR code using ID
    $qrPath = QRGenerator::generate((string)$ticketId);

    // 📝 Update ticket_code and qr_code
    $update = $pdo->prepare("UPDATE tickets SET ticket_code = ?, qr_code = ? WHERE id = ?");
    $update->execute([$ticketId, basename($qrPath), $ticketId]);

    // 📧 Send email with full ticket details
    Mailer::sendTicket($email, $name, $ticketId, $qrPath, $payment, $statusLabel);

    // 🖼️ Output
    echo "<h2>✅ Ticket Generated</h2>";
    echo "<p><strong>Name:</strong> $name</p>";
    echo "<p><strong>Email:</strong> $email</p>";
    echo "<p><strong>Payment Method:</strong> $payment</p>";
    echo "<p><strong>Ticket Code (ID):</strong> $ticketId</p>";
    echo "<p><strong>Status:</strong> $statusLabel</p>";
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
