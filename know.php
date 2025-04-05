<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';
require_once 'includes/comman.php'; // This should contain configured $conn and $mail

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize inputs
    $name = htmlspecialchars(trim($_POST["name"]));
    $mobileno = htmlspecialchars(trim($_POST["mobileno"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $jobtype = htmlspecialchars(trim($_POST["jobtype"]));
    $status = htmlspecialchars(trim($_POST["status"]));

    // Validate required fields
    if (!$name || !$mobileno || !$email || !$jobtype || !$status) {
        die("❌ All fields are required.");
    }

    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO know (name, mobileno, email, jobtype, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $mobileno, $email, $jobtype, $status);

    if ($stmt->execute()) {
        echo "✅ Data inserted successfully!<br>";

        // Send email using the $mail configured in comman.php
        try {
            $mail->clearAddresses(); // Clear any previous recipients
            $mail->addAddress('menagamohan26@gmail.com', 'Admin');

            $mail->isHTML(true);
            $mail->Subject = 'Profile Update';
            $mail->Body = "
                <h3>New Form Submission</h3>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Mobile No:</strong> $mobileno</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Job Type:</strong> $jobtype</p>
                <p><strong>Status:</strong> $status</p>
            ";

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
