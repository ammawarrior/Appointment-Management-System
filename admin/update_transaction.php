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

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = intval($_POST['status']);
    $user_id = $_SESSION['user_id'] ?? 0;

    $query = "SELECT full_name, email_address, lab_id, category, quantity, submission_date_selected, contact_number FROM submissions WHERE unique_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userEmail = $user['email_address'];
        $userName = $user['full_name'];

        $updateQuery = "UPDATE submissions SET status = ? WHERE unique_id = ?";
        $updateStmt = $conn->prepare($updateQuery);

        if (!$updateStmt) {
            echo "error: SQL Error: " . $conn->error;
            exit;
        }

        $updateStmt->bind_param("is", $status, $id);

        if ($updateStmt->execute()) {
            if ($updateStmt->affected_rows > 0) {
                $action = ($status == 2) ? "approved" : "rejected";
                logActivity($conn, $user_id, "$action reservation ID: $id");

                $emailSent = sendEmailNotification($userEmail, $userName, $status, $user, $id);

                if ($emailSent) {
                    echo "success";
                } else {
                    echo "error: Email not sent";
                }
            } else {
                echo "error: No rows updated. Check if the ID exists.";
            }
        } else {
            echo "error: SQL Execution Error: " . $updateStmt->error;
        }

        $updateStmt->close();
    } else {
        echo "error: User not found";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "error: Missing parameters";
}

function sendEmailNotification($userEmail, $userName, $status, $data, $unique_id) {
    $mail = new PHPMailer(true);

    $labNames = [
        1 => 'Metrology Calibration',
        2 => 'Chemical Analysis',
        3 => 'Microbiological Analysis',
        4 => 'Shelf-life Analysis',
        5 => 'Get Certificates',
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
        $mail->Subject = 'Reservation Status';

        $labName = $labNames[$data['lab_id']] ?? 'N/A';
        $body = "<h3>Hello $userName,</h3>";

        if ($status == 2) {
            $body .= "<p>We are pleased to inform you that your appointment reservation with the <strong>Department of Science and Technology - Region 10 (DOST 10)</strong> has been <strong style='color: green;'>Approved</strong>.</p>";
        } else {
            $body .= "<p>We regret to inform you that your appointment reservation with the <strong>Department of Science and Technology - Region 10 (DOST 10)</strong> has been <strong style='color: red;'>Rejected</strong>.</p>";
        }

        $body .= "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; font-family: Arial, sans-serif;'>";
        $body .= "<tr><th align='left'>Client Name:</th><td>$userName</td></tr>";
        $body .= "<tr><th align='left'>Transaction Code:</th><td>$unique_id</td></tr>";
        $body .= "<tr><th align='left'>Sample Classification:</th><td>$labName</td></tr>";
        $body .= "<tr><th align='left'>Category:</th><td>{$data['category']}</td></tr>";
        $body .= "<tr><th align='left'>Quantity:</th><td>{$data['quantity']}</td></tr>";
        $body .= "<tr><th align='left'>Date Reserved:</th><td>{$data['submission_date_selected']}</td></tr>";
        $body .= "<tr><th align='left'>Phone Number:</th><td>{$data['contact_number']}</td></tr>";
        $body .= "</table>";

        if ($status == 2) {
            $body .= "<p>Please ensure you arrive at the office on time and bring any required documents with you. If you have any questions or need to reschedule, feel free to reach out to us.</p>
        <p>Thank you and we look forward to seeing you!</p>
        <br>";
        } else {
            $body .= "<p>This may be due to scheduling conflicts or other concerns. You may rebook your appointment or contact us for clarification or assistance.</p>
        <p>We apologize for the inconvenience and thank you for your understanding.</p>
        <br>";
        }

        $body .= "<p>Thank you and we appreciate your understanding.<br><strong>DOST-X RSTL Team</strong></p>";

        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>