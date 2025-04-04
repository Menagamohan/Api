<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "booknow";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form Handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["name"]));
    $mobileno = htmlspecialchars(trim($_POST["mobileno"]));

    if (!$name || !$mobileno) {
        die("❌ Please fill out all fields.");
    }

    // Insert into Database
    $stmt = $conn->prepare("INSERT INTO  apply (name, mobileno) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $mobileno);

    if ($stmt->execute()) {
        echo "✅ Data inserted successfully!<br>";

        // Send Email Notification
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'menagamohan26@gmail.com';  // Your full Gmail address
            $mail->Password = 'ekidtmjaraacrhpw';  // Use an App Password, not your Gmail password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('menagamohan26@gmail.com', 'Booking Form');
            $mail->addAddress('menagamohan26@gmail.com'); 

            $mail->isHTML(true);
            $mail->Subject = 'New Booking Submission';
            $mail->Body = "<h3>New Form Submission</h3>
                           <p><strong>Name:</strong> $name</p>
                           <p><strong>Mobile No:</strong> $mobileno</p>";

            $mail->send();
            echo "✅ Email sent successfully!";
        } catch (Exception $e) {
            echo "❌ Mailer Error: " . $mail->ErrorInfo;
        }
    } else {
        echo "❌ Failed to insert data.";
    }

    $stmt->close();
}
$conn->close();
?>