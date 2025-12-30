<?php
require "db.php";
session_start();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($_SESSION['partner_id'])) {
    echo json_encode(["status"=>false,"message"=>"Unauthorized"]);
    exit;
}

$stmt = $conn->prepare("
UPDATE hall_owners
SET hall_name=?, hall_type=?, address=?, city=?, capacity=?
WHERE id=?
");

$stmt->bind_param(
  "ssssii",
  $data['hall_name'],
  $data['hall_type'],
  $data['address'],
  $data['city'],
  $data['capacity'],
  $_SESSION['partner_id']
);

$stmt->execute();

echo json_encode(["status"=>true,"message"=>"Hall details updated"]);
