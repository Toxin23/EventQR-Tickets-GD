<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public static function sendTicket($toEmail, $toName, $ticketCode, $qrPath)
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USER'];
        $mail->Password = $_ENV['MAIL_PASS'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = $_ENV['MAIL_PORT'];

        $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_NAME']);
        $mail->addAddress($toEmail, $toName);
        $mail->Subject = 'Your Event Ticket';
        $mail->Body = "Hi $toName,\n\nHere is your ticket code: $ticketCode";
        $mail->addAttachment($qrPath);

        $mail->send();
    }
}
