<?php
require "db.php";

$user_id = 1; // temporary (later from session)

$stmt = $conn->prepare("
    SELECT full_name, email, mobile, city
    FROM users
    WHERE id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result()->fetch_assoc();

echo json_encode([
    "status" => true,
    "data" => $result
]);
?>
