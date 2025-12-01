<?php
// 1. ปิด Error และตั้งค่า CORS
error_reporting(0);
ini_set('display_errors', 0);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// 2. เชื่อมต่อฐานข้อมูล
$conn = new mysqli("127.0.0.1", "root", "", "air_shop");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed"]);
    exit();
}

// 3. รับข้อมูล JSON
$data = json_decode(file_get_contents("php://input"), true);

// ตรวจสอบว่าข้อมูลมาครบไหม
if (
    !empty($data['firstname']) && 
    !empty($data['lastname']) && 
    !empty($data['email']) && 
    !empty($data['username']) && 
    !empty($data['password'])
) {
    $firstname = $conn->real_escape_string($data['firstname']);
    $lastname  = $conn->real_escape_string($data['lastname']);
    $email     = $conn->real_escape_string($data['email']);
    $tel       = $conn->real_escape_string($data['tel'] ?? '');
    $username  = $conn->real_escape_string($data['username']);
    $password  = $conn->real_escape_string($data['password']); // ✅ รับค่ารหัสผ่านตรงๆ

    // 4. เช็คว่า Username หรือ Email ซ้ำหรือไม่?
    $checkQuery = "SELECT customer_id FROM customers WHERE username = '$username' OR email = '$email'";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "ชื่อผู้ใช้ หรือ อีเมลนี้ ถูกใช้งานแล้ว"]);
    } else {
        // ❌ เอาการเข้ารหัสออก (password_hash)
        // $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // 5. บันทึกลงฐานข้อมูล (ใช้ $password ดิบๆ เลย)
        $sql = "INSERT INTO customers (firstname, lastname, email, tel, username, password) 
                VALUES ('$firstname', '$lastname', '$email', '$tel', '$username', '$password')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "สมัครสมาชิกสำเร็จ"]);
        } else {
            echo json_encode(["status" => "error", "message" => "SQL Error: " . $conn->error]);
        }
    }
} else {
    echo json_encode(["status" => "error", "message" => "กรุณากรอกข้อมูลให้ครบถ้วน"]);
}

$conn->close();
?>