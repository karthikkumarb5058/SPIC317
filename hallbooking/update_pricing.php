<?php
require "db.php";
session_start();

if (!isset($_SESSION['partner_id'])) {
    echo json_encode(["status"=>false,"message"=>"Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['starting_price'])) {
    echo json_encode(["status"=>false,"message"=>"starting_price required"]);
    exit;
}

$stmt = $conn->prepare("
UPDATE halls SET starting_price = ?
WHERE owner_id = ?
");

$stmt->bind_param("di", $data['starting_price'], $_SESSION['partner_id']);
$stmt->execute();

echo json_encode([
  "status"=>true,
  "message"=>"Pricing updated successfully"
]);
