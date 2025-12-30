<?php
require "db.php";
session_start();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($_SESSION['partner_id'])) {
    echo json_encode(["status"=>false,"message"=>"Unauthorized"]);
    exit;
}

if (empty($data['current_password']) || empty($data['new_password'])) {
    echo json_encode(["status"=>false,"message"=>"All fields required"]);
    exit;
}

$stmt = $conn->prepare("SELECT password FROM hall_owners WHERE id=?");
$stmt->bind_param("i", $_SESSION['partner_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!password_verify($data['current_password'], $user['password'])) {
    echo json_encode(["status"=>false,"message"=>"Current password incorrect"]);
    exit;
}

$newHash = password_hash($data['new_password'], PASSWORD_DEFAULT);

$upd = $conn->prepare("UPDATE hall_owners SET password=? WHERE id=?");
$upd->bind_param("si", $newHash, $_SESSION['partner_id']);
$upd->execute();

echo json_encode(["status"=>true,"message"=>"Password updated"]);
