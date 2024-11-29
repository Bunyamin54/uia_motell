<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // PHPMailer otomatik yükleme

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = htmlspecialchars($_POST['message']); // Mesaj verisini al

    // PHPMailer ile e-posta gönderimi
    $mail = new PHPMailer(true);

    try {
        // SMTP ayarları
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // SMTP sunucusu
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com'; // Gönderen e-posta adresi
        $mail->Password = 'your-email-password'; // Gönderen e-posta şifresi
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Gönderici ve alıcı bilgileri
        $mail->setFrom('your-email@gmail.com', 'Motel Contact Form');
        $mail->addAddress('recipient-email@example.com'); // Alıcı e-posta adresi

        // E-posta içeriği
        $mail->isHTML(true);
        $mail->Subject = 'New Message from Contact Form';
        $mail->Body = "<p>$message</p>";

        // E-posta gönderimi
        $mail->send();
        echo "<script>alert('Message sent successfully!');</script>";
    } catch (Exception $e) {
        echo "<script>alert('Message could not be sent. Error: {$mail->ErrorInfo}');</script>";
    }
}
?>
