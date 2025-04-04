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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // Sanitize input data
    $firstname = htmlspecialchars(trim($_POST["stFirstname"]));
    $mobileno = htmlspecialchars(trim($_POST["stMobileno"]));
    $location = htmlspecialchars(trim($_POST["stLocation"]));
    $companyname = htmlspecialchars(trim($_POST["stCompanyname"]));

    if (!$firstname || !$mobileno || !$location || !$companyname) {
        die("❌ Please fill out all fields.");
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO details (Firstname, Mobileno, Location, Companyname) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $firstname, $mobileno, $location, $companyname);

    if ($stmt->execute()) {
        echo "✅ Data inserted successfully!<br>";

        // Send Email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'menagamohan26@gmail.com';  // Your full Gmail address
            $mail->Password = 'ekidtmjaraacrhpw';  // Use the App Password, not your Gmail password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('menagamohan26@gmail.com', 'Booking Form');
            $mail->addAddress('menagamohan26@gmail.com');

            $mail->isHTML(true);
            $mail->Subject = 'New Booking Submission';
            $mail->Body = "<h3>New Form Submission</h3>
                           <p><strong>Name:</strong> $firstname</p>
                           <p><strong>Mobile No:</strong> $mobileno</p>
                           <p><strong>Location:</strong> $location</p>
                           <p><strong>Company Name:</strong> $companyname</p>";

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
