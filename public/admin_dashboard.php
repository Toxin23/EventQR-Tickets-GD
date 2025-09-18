<?php
require_once 'session.php';
require_once __DIR__ . '/../config/db.php';

$total = $pdo->query("SELECT COUNT(*) FROM tickets")->fetchColumn();
$scanned = $pdo->query("SELECT COUNT(*) FROM tickets WHERE status_label = 'Scanned'")->fetchColumn();
$paid = $pdo->query("SELECT COUNT(*) FROM tickets WHERE status_label = 'Paid'")->fetchColumn();

$tickets = $pdo->query("SELECT * FROM tickets ORDER BY id DESC")->fetchAll();
?>

<h2>📊 EventQR Admin Dashboard</h2>
<ul>
  <li>Total Tickets: <?= $total ?></li>
  <li>Paid: <?= $paid ?></li>
  <li>Scanned: <?= $scanned ?></li>
</ul>

<table border="1" cellpadding="5">
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Payment</th>
    <th>Status</th>
    <th>QR</th>
  </tr>
  <?php foreach ($tickets as $t): ?>
    <tr>
      <td><?= $t['id'] ?></td>
      <td><?= $t['name'] ?></td>
      <td><?= $t['email'] ?></td>
      <td><?= $t['payment_method'] ?></td>
      <td><?= $t['status_label'] ?></td>
      <td><img src="qrcodes/<?= $t['qr_code'] ?>" width="50"></td>
    </tr>
  <?php endforeach; ?>
</table>
