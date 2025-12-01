<?php
// 1. ปิด Error ที่อาจกวน JSON
error_reporting(E_ALL);
ini_set('display_errors', 0);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// เริ่มต้น Buffer
ob_start();

// 2. เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "air_shop");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    ob_end_clean();
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

// ==========================================
// 1. GET: ดึงข้อมูล
// ==========================================
if ($method == 'GET') {
    $sql = "SELECT * FROM employees ORDER BY employee_id DESC";
    $result = $conn->query($sql);
    $employees = [];
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $row['id'] = $row['employee_id'];
            unset($row['password']); 
            
            // ✅ แก้ไขตรงนี้: ตัด Path ทิ้งให้เหลือแค่ชื่อไฟล์ (เผื่อข้อมูลเก่าเก็บ path มาผิด)
            if (!empty($row['profile_picture'])) {
                $row['profile_picture'] = basename($row['profile_picture']);
            }

            $employees[] = $row;
        }
    }
    
    ob_end_clean(); 
    echo json_encode($employees);
    $conn->close();
    exit(); 
}

// ==========================================
// 2. POST: เพิ่ม/แก้ไข (พร้อม Upload รูป)
// ==========================================
elseif ($method == 'POST') {
    if (empty($_POST) && empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0) {
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => "ไฟล์ใหญ่เกินกำหนด (post_max_size)"]);
        exit();
    }

    $id = isset($_POST['employee_id']) ? intval($_POST['employee_id']) : (isset($_POST['id']) ? intval($_POST['id']) : 0);

    $first_name = $conn->real_escape_string($_POST['first_name'] ?? '');
    $last_name  = $conn->real_escape_string($_POST['last_name'] ?? '');
    $username   = $conn->real_escape_string($_POST['username'] ?? '');

    // --- ส่วนจัดการรูปภาพ ---
    $imageUpdateSQL = ""; 
    $profile_picture = null;
    $debugMessage = "";
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        
        $baseDir = dirname(__DIR__); 
        $uploadDir = $baseDir . '/uploads/';
        
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                $err = error_get_last();
                ob_end_clean();
                echo json_encode(["status" => "error", "message" => "สร้างโฟลเดอร์ไม่ได้: " . $err['message']]);
                exit();
            }
        }

        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $fileName = time() . '_' . uniqid() . '.' . $extension;
        $targetFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            $profile_picture = $fileName;
            $imageUpdateSQL = ", profile_picture='$fileName'";
            // $debugMessage = "\n(บันทึกรูปภาพไว้ที่: $targetFilePath)";
        } else {
            $err = error_get_last();
            ob_end_clean();
            echo json_encode(["status" => "error", "message" => "ย้ายไฟล์ไม่สำเร็จ: " . $err['message']]);
            exit();
        }

    } elseif (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => "Upload Error Code: " . $_FILES['image']['error']]);
        exit();
    }

    // --- SQL Update / Insert ---
    if ($id > 0) {
        $sql = "UPDATE employees SET 
                first_name='$first_name', 
                last_name='$last_name', 
                username='$username'
                $imageUpdateSQL 
                WHERE employee_id=$id";
        
        if (!empty($_POST['password'])) {
            $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $conn->query("UPDATE employees SET password='$passwordHash' WHERE employee_id=$id");
        }

        if ($conn->query($sql) === TRUE) {
            ob_end_clean();
            echo json_encode(["status" => "success", "message" => "แก้ไขข้อมูลสำเร็จ" . $debugMessage]);
        } else {
            ob_end_clean();
            echo json_encode(["status" => "error", "message" => "SQL Error: " . $conn->error]);
        }
    } 
    else {
        $rawPass = !empty($_POST['password']) ? $_POST['password'] : '1234';
        $passwordHash = password_hash($rawPass, PASSWORD_DEFAULT);
        $imgField = $profile_picture ? "'$profile_picture'" : "NULL";

        $sql = "INSERT INTO employees (first_name, last_name, username, password, profile_picture) 
                VALUES ('$first_name', '$last_name', '$username', '$passwordHash', $imgField)";

        if ($conn->query($sql) === TRUE) {
            ob_end_clean();
            echo json_encode(["status" => "success", "message" => "เพิ่มพนักงานสำเร็จ" . $debugMessage]);
        } else {
            ob_end_clean();
            echo json_encode(["status" => "error", "message" => "SQL Error: " . $conn->error]);
        }
    }
    $conn->close();
    exit(); 
}

// ==========================================
// 3. DELETE: ลบข้อมูล
// ==========================================
elseif ($method == 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = 0;
    if (!empty($data['id'])) $id = intval($data['id']);
    elseif (!empty($data['employee_id'])) $id = intval($data['employee_id']);

    if ($id > 0) {
        $q = $conn->query("SELECT profile_picture FROM employees WHERE employee_id = $id");
        if ($q && $r = $q->fetch_assoc()) {
            if ($r['profile_picture']) {
                $baseDir = dirname(__DIR__);
                $filePath = $baseDir . '/uploads/' . $r['profile_picture'];
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
        }

        if ($conn->query("DELETE FROM employees WHERE employee_id = $id") === TRUE) {
            ob_end_clean();
            echo json_encode(["status" => "success", "message" => "ลบข้อมูลสำเร็จ"]);
        } else {
            ob_end_clean();
            echo json_encode(["status" => "error", "message" => "SQL Error: " . $conn->error]);
        }
    } else {
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => "ไม่พบ ID พนักงาน"]);
    }
    $conn->close();
    exit(); 
}

ob_end_clean();
$conn->close();
?>