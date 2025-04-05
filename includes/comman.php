<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php'; // Adjust the path if necessary


// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "booknow";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// comman.php
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'menagamohan26@gmail.com';  // Your Gmail
    $mail->Password = 'ekidtmjaraacrhpw';         // App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->setFrom('menagamohan26@gmail.com', 'Booking Form');
} catch (Exception $e) {
    echo "❌ Mailer setup error: " . $mail->ErrorInfo;
}
?>

