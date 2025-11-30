<?php
error_reporting(0);
ini_set('display_errors', 0);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// เชื่อมต่อ air_shop
$conn = new mysqli("localhost", "root", "", "air_shop");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

$json_input = file_get_contents("php://input");
$data = json_decode($json_input, true);

if (is_null($data)) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON input"]);
    exit();
}

// รับค่าต่าง ๆ
$flight_id = intval($data['flight_id'] ?? 0);
$airline = $conn->real_escape_string($data['airline'] ?? '');
$origin = $conn->real_escape_string($data['origin'] ?? '');
$destination = $conn->real_escape_string($data['destination'] ?? '');
$depart = $conn->real_escape_string($data['depart'] ?? '');
$passengers = intval($data['passengers'] ?? 1);
$total_price = floatval($data['total_price'] ?? 0);

if (empty($airline) || empty($total_price)) {
    echo json_encode(["status" => "error", "message" => "Incomplete Data"]);
    exit();
}

// บันทึกลง Database
$sql = "INSERT INTO bookings (flight_id, airline, origin, destination, depart_time, passengers, total_price, status) 
        VALUES ($flight_id, '$airline', '$origin', '$destination', '$depart', $passengers, $total_price, 'PENDING')";

if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
    echo json_encode([
        "status" => "success", 
        "message" => "Booking created successfully",
        "booking_id" => $last_id
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "SQL Error: " . $conn->error]);
}

$conn->close();
?>