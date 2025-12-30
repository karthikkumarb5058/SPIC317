<?php
require "db.php";
session_start();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($_SESSION['partner_id'])) {
    echo json_encode(["status"=>false,"message"=>"Unauthorized"]);
    exit;
}

if (empty($data['full_name']) || empty($data['mobile'])) {
    echo json_encode(["status"=>false,"message"=>"Required fields missing"]);
    exit;
}

$stmt = $conn->prepare("
UPDATE hall_owners
SET full_name=?, mobile=?
WHERE id=?
");

$stmt->bind_param(
  "ssi",
  $data['full_name'],
  $data['mobile'],
  $_SESSION['partner_id']
);

$stmt->execute();

echo json_encode(["status"=>true,"message"=>"Profile updated"]);
