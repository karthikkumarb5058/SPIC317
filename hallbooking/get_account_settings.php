<?php
require "db.php";

$user_id = 1;

$stmt = $conn->prepare("
    SELECT notifications, dark_mode, language
    FROM user_settings
    WHERE user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result()->fetch_assoc();

echo json_encode([
    "status" => true,
    "data" => $result
]);
?>
