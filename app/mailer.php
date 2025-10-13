
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function send_html_mail(string $toEmail, string $toName, string $subject, string $html): bool {
    // Save a copy to storage/outbox regardless of SMTP result
    $outboxDir = __DIR__ . '/../storage/outbox';
    if (!is_dir($outboxDir)) { @mkdir($outboxDir, 0777, true); }
    $fname = $outboxDir . '/email_' . date('Ymd_His') . '_' . preg_replace('/[^a-z0-9]+/i','_', $toEmail) . '.html';
    file_put_contents($fname, $html);

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = MAIL_HOST;
        $mail->Port = MAIL_PORT;
        if (MAIL_USERNAME) {
            $mail->SMTPAuth = true;
            $mail->Username = MAIL_USERNAME;
            $mail->Password = MAIL_PASSWORD;
        }
        if (MAIL_SECURE) {
            $mail->SMTPSecure = MAIL_SECURE;
        }
        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress($toEmail, $toName);
        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->Body = $html;
        $mail->AltBody = strip_tags($html);
        return $mail->send();
    } catch (Exception $e) {
        // Log error silently; outbox copy is still available
        error_log('Mailer error: ' . $e->getMessage());
        return false;
    }
}
