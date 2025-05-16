<?php
require 'temp_db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $query = "SELECT * FROM submissions WHERE unique_id = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die(json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]));
    }

    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        echo json_encode(["status" => "success"] + $data);
    } else {
        echo json_encode(["status" => "error", "message" => "No data found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
