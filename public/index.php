<?php
// ✅ Import required classes before any logic
use Endroid\QrCode\QrCode;
use Dompdf\Dompdf;

// ✅ Load dependencies
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $event_name = $_POST['event_name'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    $ticket_code = uniqid('TKT-');
    $status_label = 'Pending';

    // Generate QR code content
    $qr_content = "Name: $name\nEmail: $email\nEvent: $event_name\nCode: $ticket_code";

    // Generate QR image
    $qrCode = new QrCode($qr_content);
    $qrCode->setSize(200);
    $qr_image = base64_encode($qrCode->writeString());

    // Save to database
    $stmt = $pdo->prepare("INSERT INTO tickets (name, email, event_name, ticket_code, qr_code, payment_method, status_label) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $event_name, $ticket_code, $qr_image, $payment_method, $status_label]);

    // Generate PDF ticket
    $dompdf = new Dompdf();
    $html = "
        <h2>Event Ticket</h2>
        <p><strong>Name:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Event:</strong> $event_name</p>
        <p><strong>Ticket Code:</strong> $ticket_code</p>
        <img src='data:image/png;base64,$qr_image' />
    ";
    $dompdf->loadHtml($html);
    $dompdf->render();
    $dompdf->stream("ticket_$ticket_code.pdf", ["Attachment" => false]);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>EventQR Ticket Generator</title>
</head>
<body>
    <h1>Get Your Event Ticket</h1>
    <form method="POST">
        <label>Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Event Name:</label><br>
        <input type="text" name="event_name" required><br><br>

        <label>Payment Method:</label><br>
        <select name="payment_method" required>
            <option value="Cash">Cash</option>
            <option value="EFT">EFT</option>
            <option value="Card">Card</option>
        </select><br><br>

        <button type="submit">Generate Ticket</button>
    </form>
</body>
</html>
