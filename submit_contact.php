<?php include 'visit_logger.php'; ?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMaier\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form fields and sanitize inputs
    $name = htmlspecialchars(strip_tags($_POST['name']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(strip_tags($_POST['message']));

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings for Gmail SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ogaugeandcode@gmail.com'; // Replace with your Gmail address
        $mail->Password = 'kqxs mihq vqpq kysc';    // Replace with your app password (not Gmail password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        // Recipients
        $mail->setFrom($email, $name);               // Sender's email and name
        $mail->addAddress('ogaugendcode@gmail.com'); // Your email where messages are sent

        // Content
        $mail->isHTML(false); // Set to false for plain text email
        $mail->Subject = 'New Message from Contact Form';
        $mail->Body = "Name: $name\nEmail: $email\n\nMessage:\n$message";

        // Send the email
        if ($mail->send()) {
            echo "<h1>Thank you, $name! Your message has been sent successfully.</h1>";
            echo "<p><a href='contact.html'>Back to Contact Page</a></p>";
        }
    } catch (Exception $e) {
        echo "<h1>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</h1>";
        echo "<p><a href='contact.html'>Back to Contact Page</a></p>";
    }
} else {
    // Redirect to contact form if the page is accessed without submitting the form
    header("Location: contact.html");
    exit();
}
?>

