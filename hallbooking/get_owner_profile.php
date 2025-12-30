<?php
require "db.php";
session_start();

if (!isset($_SESSION['partner_id'])) {
    echo json_encode(["status"=>false,"message"=>"Unauthorized"]);
    exit;
}

$stmt = $conn->prepare("
SELECT full_name, email, mobile, hall_name, hall_type, address, city
FROM hall_owners
WHERE id = ?
");
$stmt->bind_param("i", $_SESSION['partner_id']);
$stmt->execute();

echo json_encode([
  "status"=>true,
  "profile"=>$stmt->get_result()->fetch_assoc()
]);
