<?php

declare(strict_types=1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/PHPMailer/Exception.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/PHPMailer/SMTP.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/PHPMailer/PHPMailer.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    public static function send(string $email, string $html): bool
    {
        try {

            $mail = new PHPMailer(true);

            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = (int) $_ENV['MAIL_PORT'];
            $mail->CharSet = 'UTF-8';

            $mail->setFrom($_ENV['MAIL_USERNAME'], 'Transcooler ERP');

            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Solicitud de descarga de archivo';
            $mail->Body = $html;
            $mail->send();
            return true;

        } catch (Exception $e) {

            echo $e->getMessage();

            return false;
        }
    }
}