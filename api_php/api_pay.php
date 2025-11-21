<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { http_response_code(200); exit(); }

$conn = new mysqli("localhost", "root", "", "air_shop");
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->booking_id)) {
    $id = $data->booking_id;
    // อัปเดตเป็น 'paid' (จ่ายแล้ว)
    $sql = "UPDATE bookings SET payment_status = 'paid' WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}
$conn->close();
?>