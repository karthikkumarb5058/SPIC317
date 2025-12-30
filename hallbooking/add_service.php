<?php
require "db.php";
session_start();

if (!isset($_SESSION['partner_id'])) {
    echo json_encode(["status"=>false,"message"=>"Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['hall_id']) || empty($data['service_name']) || empty($data['service_price'])) {
    echo json_encode(["status"=>false,"message"=>"All fields required"]);
    exit;
}

$stmt = $conn->prepare("
INSERT INTO hall_services (hall_id, service_name, service_price)
VALUES (?,?,?)
");

$stmt->bind_param(
    "isd",
    $data['hall_id'],
    $data['service_name'],
    $data['service_price']
);

$stmt->execute();

echo json_encode([
  "status"=>true,
  "message"=>"Service added successfully"
]);
