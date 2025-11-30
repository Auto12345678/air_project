<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "", "air_shop");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed"]));
}

$id = isset($_GET['id']) ? $_GET['id'] : '';

if ($id) {
    $id = $conn->real_escape_string($id);
    $sql = "SELECT * FROM bookings WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(["status" => "error", "message" => "Not found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No ID provided"]);
}

$conn->close();
?>