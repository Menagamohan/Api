<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';
require_once 'includes/comman.php'; // Contains DB connection and $mail setup

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

        // Send Email using existing $mail from comman.php
        try {
            $mail->clearAddresses(); // Clear previous addresses if reused
            $mail->addAddress('menagamohan26@gmail.com', 'Admin');

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
