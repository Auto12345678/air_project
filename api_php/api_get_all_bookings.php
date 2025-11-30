<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "", "air_shop");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed"]));
}

// ไม่ต้อง JOIN แล้ว ดึงข้อมูลดิบๆ เลย (มี customer_id อยู่แล้ว)
$sql = "SELECT * FROM bookings ORDER BY id DESC";
$result = $conn->query($sql);

$bookings = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}

echo json_encode($bookings);
$conn->close();
?>