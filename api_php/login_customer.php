<?php
// 1. ตั้งค่า Header และ Error Handling
error_reporting(0);
ini_set('display_errors', 0);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");


$conn = new mysqli("localhost", "root", "", "air_shop");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed: " . $conn->connect_error
    ]);
    exit();
}

// 3. รับข้อมูล JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['username']) || !isset($data['password'])) {
    echo json_encode([
        "success" => false,
        "message" => "กรุณากรอกชื่อผู้ใช้และรหัสผ่าน"
    ]);
    exit;
}

$username = $conn->real_escape_string(trim($data['username']));
$password = $conn->real_escape_string(trim($data['password']));

// 4. ค้นหาลูกค้า (เทียบ username และ password ตรงๆ เพราะไม่ได้ Hash)
$sql = "SELECT * FROM customers WHERE username = '$username' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // ตัดรหัสผ่านออกก่อนส่งกลับ เพื่อความปลอดภัย
    unset($user['password']);

    // แมพ customer_id เป็น id เพื่อให้ frontend ใช้ง่าย (ถ้าต้องการ)
    $user['id'] = $user['customer_id'];

    echo json_encode([
        "success" => true,
        "message" => "Login successful.",
        "user" => $user
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง"
    ]);
}

$conn->close();
?>