<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/QRGenerator.php';
require_once __DIR__ . '/../src/Mailer.php';

use App\QRGenerator;
use App\Mailer;

// 🔐 Encrypt ticket code: letters → numbers, digits stay, symbols → 99
function encryptTicketCode(string $code): string {
    $map = array_flip(range('A', 'Z')); // A=0, B=1, ..., Z=25
    $encoded = '';

    foreach (str_split(strtoupper($code)) as $char) {
        if (ctype_alpha($char)) {
            $encoded .= str_pad($map[$char] + 1, 2, '0', STR_PAD_LEFT);
        } elseif (ctype_digit($char)) {
            $encoded .= $char;
        } else {
            $encoded .= '99'; // Optional: encode symbols as 99
        }
    }

    return $encoded;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? 'Guest');
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $payment = htmlspecialchars($_POST['payment'] ?? 'Unknown');

    if (!$email) {
        echo "Invalid email address.";
        exit;
    }

    // 🔄 Generate and encrypt ticket code
    $ticketCodeRaw = 'TKT_' . uniqid();
    $ticketCodeEncrypted = encryptTicketCode($ticketCodeRaw);

    // 🎯 Generate QR code
    $qrPath = QRGenerator::generate($ticketCodeEncrypted);

    // 💾 Save to database
    $stmt = $pdo->prepare("INSERT INTO tickets (name, email, payment_method, ticket_code, qr_code, payment_status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $name,
        $email,
        $payment,
        $ticketCodeEncrypted,
        basename($qrPath),
        'Paid'
    ]);

    // 📧 Send email
    Mailer::sendTicket($email, $name, $ticketCodeEncrypted, $qrPath);

    // 🖼️ Output
    echo "<h2>✅ Ticket Generated</h2>";
    echo "<p><strong>Name:</strong> $name</p>";
    echo "<p><strong>Email:</strong> $email</p>";
    echo "<p><strong>Payment Method:</strong> $payment</p>";
    echo "<p><strong>Encrypted Ticket Code:</strong> $ticketCodeEncrypted</p>";
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
