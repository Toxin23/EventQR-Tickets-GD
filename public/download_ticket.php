<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;

// Collect POST data
$code = $_POST['ticket_code'] ?? 'Unknown';
$name = $_POST['name'] ?? 'Guest';
$event = $_POST['event'] ?? 'EventQR';

// Path to QR code image
$qrFile = __DIR__ . "/qrcodes/$code.png";

// Check if QR file exists
if (!file_exists($qrFile)) {
    echo "❌ QR code not found.";
    exit;
}

// Convert QR image to base64
$qrData = base64_encode(file_get_contents($qrFile));
$qrImage = "data:image/png;base64,$qrData";

// HTML content for the PDF
$html = "
  <style>
    body { font-family: Arial, sans-serif; }
    .ticket-box {
      border: 2px dashed #333;
      padding: 20px;
      width: 400px;
      margin: auto;
      text-align: center;
    }
    h1 { color: #0057b7; }
    img { margin-top: 15px; }
  </style>
  <div class='ticket-box'>
    <h1>🎟️ EventQR Ticket</h1>
    <p><strong>Name:</strong> $name</p>
    <p><strong>Event:</strong> $event</p>
    <p><strong>Ticket Code:</strong> $code</p>
    <img src='$qrImage' width='150' alt='QR Code'>
    <p style='margin-top: 10px;'>Present this QR code at the entrance</p>
  </div>
";

// Generate and stream PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A5', 'portrait');
$dompdf->render();
$dompdf->stream("ticket_$code.pdf", ["Attachment" => true]);
