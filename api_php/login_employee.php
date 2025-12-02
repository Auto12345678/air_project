<?php
// 1. ตั้งค่า Header และ Error Handling
error_reporting(0);
ini_set('display_errors', 0);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// 2. เชื่อมต่อฐานข้อมูล (ใช้ mysqli ให้เหมือนไฟล์อื่นๆ ในโปรเจกต์)
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
$password = trim($data['password']);

// 4. ค้นหาข้อมูลจาก Username (ยังไม่เช็ครหัสผ่านตรงนี้)
$sql = "SELECT * FROM employees WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // 5. ✅ ตรวจสอบรหัสผ่านด้วย password_verify
    // (ระบบจะเอารหัสที่พิมพ์ $password ไปเทียบกับ Hash ใน $user['password'])
    if (password_verify($password, $user['password'])) {
        
        // ตัดรหัสผ่านออกก่อนส่งกลับ เพื่อความปลอดภัย
        unset($user['password']);

        echo json_encode([
            "success" => true,
            "message" => "Login successful.",
            "user" => $user // ส่งข้อมูลพนักงานกลับไป (เช่น id, ชื่อ, ตำแหน่ง)
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "รหัสผ่านไม่ถูกต้อง"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "ไม่พบชื่อผู้ใช้นี้ในระบบ"
    ]);
}

$conn->close();
?>