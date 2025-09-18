<?php
namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    public static function sendTicket($toEmail, $toName, $ticketCode, $qrPath, $paymentMethod, $statusLabel) {
        $mail = new PHPMailer(true);

        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USER'];
            $mail->Password = $_ENV['MAIL_PASS'];
            $mail->SMTPSecure = 'tls';
            $mail->Port = $_ENV['MAIL_PORT'];

            // Sender and recipient
            $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_NAME']);
            $mail->addAddress($toEmail, $toName);

            // Subject and body
            $mail->Subject = 'Your Event Ticket Confirmation';
            $mail->isHTML(true);

            $mail->Body = <<<HTML
                <h2>🎟️ Your Ticket Details</h2>
                <p><strong>Name:</strong> {$toName}</p>
                <p><strong>Email:</strong> {$toEmail}</p>
                <p><strong>Ticket Code:</strong> {$ticketCode}</p>
                <p><strong>Payment Method:</strong> {$paymentMethod}</p>
                <p><strong>Status:</strong> {$statusLabel}</p>
                <p>Please find your QR code attached. Present it at the entrance for verification.</p>
                <br>
                <p>Thank you for registering!<br><strong>EventQR Team</strong></p>
            HTML;

            // Attach QR code
            $mail->addAttachment($qrPath);

            // Send the email
            $mail->send();
        } catch (Exception $e) {
            error_log("Mailer Error: " . $mail->ErrorInfo);
        }
    }
}
