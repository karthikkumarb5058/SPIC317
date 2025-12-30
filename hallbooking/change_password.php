<?php
require "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$user_id = 1;

$stmt = $conn->prepare("
    SELECT password
    FROM users
    WHERE id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$storedPassword = $stmt->get_result()->fetch_assoc()['password'];

if (!password_verify($data['current_password'], $storedPassword)) {
    echo json_encode([
        "status" => false,
        "message" => "Current password is incorrect"
    ]);
    exit;
}

$newPassword = password_hash($data['new_password'], PASSWORD_DEFAULT);

$update = $conn->prepare("
    UPDATE users
    SET password = ?
    WHERE id = ?
");
$update->bind_param("si", $newPassword, $user_id);
$update->execute();

echo json_encode([
    "status" => true,
    "message" => "Password changed successfully"
]);
?>
