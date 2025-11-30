<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "air_shop");
$conn->set_charset("utf8");

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['booking_id'])) {
    $booking_id = $conn->real_escape_string($data['booking_id']);
    $sql = "UPDATE bookings SET status = 'PAID' WHERE id = '$booking_id'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Payment confirmed"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Update failed"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
}
$conn->close();
?>