<!DOCTYPE html>
<html>
<head>
  <title>Scan Ticket</title>
  <script src="https://unpkg.com/html5-qrcode"></script>
  <style>
    body { font-family: Arial; text-align: center; padding: 20px; }
    #result { margin-top: 20px; font-size: 18px; }
    .success { color: green; }
    .error { color: red; }
  </style>
</head>
<body>
  <h2>🎟️ Scan Ticket QR Code</h2>
  <div id="reader" style="width: 300px; margin: auto;"></div>
  <div id="result"></div>

  <script>
    function validateTicket(ticketCode) {
      fetch('validate_ticket.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'ticket_code=' + encodeURIComponent(ticketCode)
      })
      .then(res => res.json())
      .then(data => {
        const result = document.getElementById('result');
        if (data.status === 'success') {
          result.innerHTML = `✅ Ticket Validated: ${data.name}`;
          result.className = 'success';
        } else {
          result.innerHTML = `❌ ${data.message}`;
          result.className = 'error';
        }
      });
    }

    const html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start(
      { facingMode: "environment" },
      { fps: 10, qrbox: 250 },
      qrCodeMessage => {
        html5QrCode.stop();
        validateTicket(qrCodeMessage);
      },
      errorMessage => {}
    );
  </script>
</body>
</html>
