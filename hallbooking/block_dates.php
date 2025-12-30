<?php
require "db.php";
session_start();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($_SESSION['partner_id'])) {
    echo json_encode(["status"=>false,"message"=>"Unauthorized"]);
    exit;
}

if (empty($data['start_date']) || empty($data['end_date'])) {
    echo json_encode(["status"=>false,"message"=>"Dates required"]);
    exit;
}

$stmt = $conn->prepare("
INSERT INTO blocked_dates (hall_id,start_date,end_date,reason)
VALUES (?,?,?,?)
");

$stmt->bind_param(
  "isss",
  $_SESSION['partner_id'],
  $data['start_date'],
  $data['end_date'],
  $data['reason']
);

$stmt->execute();

echo json_encode(["status"=>true,"message"=>"Dates blocked"]);
