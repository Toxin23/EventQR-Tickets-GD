<?php
require_once 'session.php';
require_once __DIR__ . '/../config/db.php';
$tickets = $pdo->query("SELECT * FROM tickets ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Tickets</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
    select { padding: 4px; }
  </style>
</head>
<body>
  <h2>🎟️ Admin Ticket Manager</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Payment</th>
      <th>Status</th>
      <th>Update</th>
    </tr>
    <?php foreach ($tickets as $t): ?>
      <tr>
        <td><?= $t['id'] ?></td>
        <td><?= $t['name'] ?></td>
        <td><?= $t['email'] ?></td>
        <td><?= $t['payment_method'] ?></td>
        <td>
          <select class="status-dropdown" data-id="<?= $t['id'] ?>">
            <?php
              $statuses = ['Paid', 'Unpaid', 'Confirmed', 'Scanned'];
              foreach ($statuses as $s) {
                $selected = ($t['status_label'] === $s) ? 'selected' : '';
                echo "<option value='$s' $selected>$s</option>";
              }
            ?>
          </select>
        </td>
        <td><span id="status-msg-<?= $t['id'] ?>"></span></td>
      </tr>
    <?php endforeach; ?>
  </table>

  <script>
    $('.status-dropdown').change(function() {
      const ticketId = $(this).data('id');
      const newStatus = $(this).val();
      $.post('update_status.php', { id: ticketId, status: newStatus }, function(response) {
        $('#status-msg-' + ticketId).text(response.message);
      }, 'json');
    });
  </script>
</body>
</html>
