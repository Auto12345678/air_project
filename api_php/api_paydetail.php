<?php
// flight_api/api_get_booking_detail.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "", "flight_booking_db");

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    // ดึงข้อมูลลูกค้าและการจองจาก ID
    $sql = "SELECT * FROM bookings WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["status" => "error", "message" => "ไม่พบข้อมูลการจอง"]);
    }
}
$conn->close();
?>