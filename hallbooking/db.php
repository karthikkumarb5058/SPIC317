<?php
$conn = new mysqli("localhost", "root", "", "hall_booking");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        "status" => false,
        "message" => "Database connection failed"
    ]);
    exit;
}

$conn->set_charset("utf8");
header("Content-Type: application/json");
?>
