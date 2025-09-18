<?php
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

$id = $_POST['id'] ?? null;
$status = $_POST['status'] ?? null;

if (!$id || !$status) {
  echo json_encode(['message' => 'Missing data']);
  exit;
}

$valid = ['Paid', 'Unpaid', 'Confirmed', 'Scanned'];
if (!in_array($status, $valid)) {
  echo json_encode(['message' => 'Invalid status']);
  exit;
}

$stmt = $pdo->prepare("UPDATE tickets SET status_label = ? WHERE id = ?");
$stmt->execute([$status, $id]);

echo json_encode(['message' => 'Status updated']);
