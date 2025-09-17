<?php
require_once '../src/QRGenerator.php';

$ticketCode = 'TKT_' . uniqid();
$qrPath = QRGenerator::generate($ticketCode);

echo "<h2>QR Code Generated</h2>";
echo "<p>Ticket Code: $ticketCode</p>";
echo "<img src='../$qrPath' alt='QR Code'>";
