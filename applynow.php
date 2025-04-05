<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';
require 'includes/comman.php'; // Only include this once (contains DB and mail config)

// Form Handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["name"]));
    $mobileno = htmlspecialchars(trim($_POST["mobileno"]));

    if (!$name || !$mobileno) {
        die("❌ Please fill out all fields.");
    }

    // Insert into Database
    $stmt = $conn->prepare("INSERT INTO apply (name, mobileno) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $mobileno);

    if ($stmt->execute()) {
        echo "✅ Data inserted successfully!<br>";

        // Send Email Notification
        try {
            $mail->addAddress('menagamohan26@gmail.com', 'Admin'); // ✅ Update to your email
            $mail->isHTML(true);
            $mail->Subject = 'New Apply Submission';
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
    $conn->close();
}
?>
