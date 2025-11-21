<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { http_response_code(200); exit(); }

$conn = new mysqli("localhost", "root", "", "air_shop");

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->flight_id)) {
    $flight_id = $data->flight_id;
    $airline = $data->airline;
    $depart = $data->depart;
    $passengers = $data->passengers;
    $total_price = $data->total_price;

    // บันทึกโดยให้สถานะเป็น pending (รอจ่าย)
    $sql = "INSERT INTO bookings (flight_id, airline, depart_time, passengers, total_price, payment_status) 
            VALUES ('$flight_id', '$airline', '$depart', '$passengers', '$total_price', 'pending')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode([
            "status" => "success", 
            "booking_id" => $conn->insert_id // ส่ง ID กลับไปเพื่อใช้หน้าจ่ายเงิน
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}
$conn->close();
?>