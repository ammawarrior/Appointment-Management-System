<?php
require 'temp_db.php'; // Database connection
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function logActivity($conn, $user_id, $activity) {
    $stmt = $conn->prepare("SELECT code_name FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $code_name = "Unknown User";
    if ($row = $result->fetch_assoc()) {
        $code_name = $row['code_name'] ?? $code_name;
    }

    $formatted_activity = "$code_name $activity";
    $stmt = $conn->prepare("INSERT INTO user_activity (user_id, activity) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $formatted_activity);
    $stmt->execute();
    $stmt->close();
}

if (isset($_POST['id']) && isset($_POST['submission_date_selected'])) {
    $id = intval($_POST['id']);
    $new_date = $_POST['submission_date_selected'];
    $user_id = $_SESSION['user_id'] ?? 0;

    $query = "SELECT submission_id, full_name, email_address, lab_id, category, quantity, submission_date_selected, contact_number FROM submissions WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $submission = $result->fetch_assoc();
        $userEmail = $submission['email_address'];
        $userName = $submission['full_name'];
        $submission_id = $submission['submission_id'];

        $updateQuery = "UPDATE submissions SET submission_date_selected = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);

        if (!$updateStmt) {
            echo "error: SQL Error: " . $conn->error;
            exit;
        }

        $updateStmt->bind_param("si", $new_date, $id);

        if ($updateStmt->execute()) {
            if ($updateStmt->affected_rows > 0) {
                logActivity($conn, $user_id, "updated the appointment date for submission ID: $submission_id");

                $emailSent = sendEmailNotification($userEmail, $userName, $submission, $new_date, $submission_id);

                if ($emailSent) {
                    echo "success";
                } else {
                    echo "error: Email not sent";
                }
            } else {
                echo "error: No rows updated. Check if the ID exists or if the date is the same.";
            }
        } else {
            echo "error: SQL Execution Error: " . $updateStmt->error;
        }

        $updateStmt->close();
    } else {
        echo "error: Submission not found";
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(400);
    echo "error: Missing parameters";
}

function sendEmailNotification($userEmail, $userName, $submission, $new_date, $submission_id) {
    $mail = new PHPMailer(true);

    $labNames = [
        1 => 'Metrology Calibration',
        2 => 'Chemical Analysis',
        3 => 'Microbiological Analysis',
        4 => 'Shelf-life Analysis',
        5 => 'Walk-in / Get Certificates',
        6 => 'General Inquiry'
    ];

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'eddiemarkbryandoverte@gmail.com';
        $mail->Password = 'uucx sptd lggg nnvl';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('eddiemarkbryandoverte@gmail.com', 'Department of Science and Technology - Region X');
        $mail->addAddress($userEmail, $userName);
        $mail->isHTML(true);
        $mail->Subject = 'Updated Appointment Date - DOST-X Reservation';

        $labName = $labNames[$submission['lab_id']] ?? 'N/A';

        $body = "<h3>Hello $userName,</h3>";
        $body .= "<p>We would like to inform you that your appointment reservation with the <strong>Department of Science and Technology - Region 10 (DOST 10)</strong> has been updated.</p>";
        $body .= "<p><strong>New Reserved Date:</strong> $new_date</p>";

        $body .= "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; font-family: Arial, sans-serif;'>";
        $body .= "<tr><th align='left'>Client Name:</th><td>$userName</td></tr>";
        $body .= "<tr><th align='left'>Transaction Code:</th><td>$submission_id</td></tr>";
        $body .= "<tr><th align='left'>Sample Classification:</th><td>$labName</td></tr>";
        $body .= "<tr><th align='left'>Category:</th><td>{$submission['category']}</td></tr>";
        $body .= "<tr><th align='left'>Quantity:</th><td>{$submission['quantity']}</td></tr>";
        $body .= "<tr><th align='left'>Phone Number:</th><td>{$submission['contact_number']}</td></tr>";
        $body .= "</table>";

        $body .= "<p>Please be guided accordingly. Should you have concerns or wish to reschedule again, feel free to contact us.</p>";
        $body .= "<br><p>Thank you.<br><strong>DOST-X RSTL Team</strong></p>";

        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
