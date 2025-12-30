<?php
require "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$user_id = 1;

$stmt = $conn->prepare("
    UPDATE user_settings
    SET notifications = ?, dark_mode = ?, language = ?
    WHERE user_id = ?
");

$stmt->bind_param(
    "iisi",
    $data['notifications'],
    $data['dark_mode'],
    $data['language'],
    $user_id
);

$stmt->execute();

echo json_encode([
    "status" => true,
    "message" => "Account settings updated"
]);
?>
