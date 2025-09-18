<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

$ticket_code = $_POST['ticket_code'] ?? '';

if (!$ticket_code) {
    echo json_encode(['status' => 'error', 'message' => 'No ticket code provided']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM tickets WHERE ticket_code = ?");
$stmt->execute([$ticket_code]);
$ticket = $stmt->fetch();

if (!$ticket) {
    echo json_encode(['status' => 'error', 'message' => 'Ticket not found']);
} elseif ($ticket['status_label'] === 'Scanned') {
    echo json_encode(['status' => 'error', 'message' => 'Ticket already scanned']);
} else {
    $update = $pdo->prepare("UPDATE tickets SET status_label = 'Scanned' WHERE ticket_code = ?");
    $update->execute([$ticket_code]);
    echo json_encode(['status' => 'success', 'message' => 'Ticket validated', 'name' => $ticket['name']]);
}
