<?php
// 1. ปิด Error ที่อาจทำให้ JSON พัง
error_reporting(0);
ini_set('display_errors', 0);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 2. เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "air_shop");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

// ---------------------------------------------------------
// 1. GET: ดึงข้อมูล
// ---------------------------------------------------------
if ($method == 'GET') {
    $sql = "SELECT * FROM customers ORDER BY customer_id DESC";
    $result = $conn->query($sql);
    
    $customers = [];
    if ($result) {
        while($row = $result->fetch_assoc()) {
            $row['id'] = $row['customer_id'];
            $customers[] = $row;
        }
    }
    echo json_encode($customers);
}

// ---------------------------------------------------------
// 2. PUT: แก้ไขข้อมูล
// ---------------------------------------------------------
elseif ($method == 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);

    // หา ID (รองรับทั้ง customer_id และ id)
    $id = 0;
    if (isset($data['customer_id'])) $id = intval($data['customer_id']);
    elseif (isset($data['id'])) $id = intval($data['id']);

    if ($id > 0) {
        $firstname = $conn->real_escape_string(isset($data['firstname']) ? $data['firstname'] : '');
        $lastname  = $conn->real_escape_string(isset($data['lastname']) ? $data['lastname'] : '');
        $email     = $conn->real_escape_string(isset($data['email']) ? $data['email'] : '');
        $username  = $conn->real_escape_string(isset($data['username']) ? $data['username'] : '');
        
        // รับเบอร์โทร (หาจาก phone ก่อน ถ้าไม่มีหาจาก tel)
        $phoneVal = '';
        if (isset($data['phone'])) $phoneVal = $conn->real_escape_string($data['phone']);
        elseif (isset($data['tel'])) $phoneVal = $conn->real_escape_string($data['tel']);

        // สร้าง SQL Update
        $sql = "UPDATE customers SET 
                firstname='$firstname', 
                lastname='$lastname', 
                email='$email', 
                tel='$phoneVal',
                username='$username'";
        
        // ถ้ามีรหัสผ่านใหม่ ให้แก้ด้วย
        if (!empty($data['password'])) {
            $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
            $sql .= ", password='$passwordHash'";
        }

        $sql .= " WHERE customer_id=$id";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "แก้ไขข้อมูลสำเร็จ"]);
        } else {
            // ถ้า SQL Error (เช่น ไม่มีคอลัมน์ username) จะส่งกลับไปบอก
            echo json_encode(["status" => "error", "message" => "SQL Error: " . $conn->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "ไม่พบ ID ลูกค้า (Customer ID missing)"]);
    }
}

// ---------------------------------------------------------
// 3. DELETE: ลบข้อมูล
// ---------------------------------------------------------
elseif ($method == 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);

    $id = 0;
    if (isset($data['customer_id'])) $id = intval($data['customer_id']);
    elseif (isset($data['id'])) $id = intval($data['id']);

    if ($id > 0) {
        $sql = "DELETE FROM customers WHERE customer_id = $id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "ลบข้อมูลสำเร็จ"]);
        } else {
            echo json_encode(["status" => "error", "message" => "SQL Error: " . $conn->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "ไม่พบ ID ลูกค้า"]);
    }
}

$conn->close();
?>