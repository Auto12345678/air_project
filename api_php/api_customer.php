<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// เชื่อมต่อฐานข้อมูล air_shop (ใช้ 127.0.0.1 ตามที่ตั้งค่าไว้)
$conn = new mysqli("127.0.0.1", "root", "", "air_shop");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed"]));
}

// ดึงข้อมูลลูกค้าทั้งหมด เรียงจากสมัครล่าสุด (แก้ไขจาก id เป็น customer_id ตามที่คุณแจ้ง)
$sql = "SELECT * FROM customers ORDER BY customer_id DESC";
$result = $conn->query($sql);

$customers = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // ตัดรหัสผ่านออกเพื่อความปลอดภัย ไม่ส่งไปหน้าเว็บ
        unset($row['password']); 
        $customers[] = $row;
    }
}

echo json_encode($customers);
$conn->close();
?>