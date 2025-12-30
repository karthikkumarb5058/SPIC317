<?php
require "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$user_id = 1;

if (empty($data['subject']) || empty($data['message'])) {
    echo json_encode([
        "status" => false,
        "message" => "Subject and message are required"
    ]);
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO support_tickets (user_id, subject, message)
    VALUES (?, ?, ?)
");
$stmt->bind_param(
    "iss",
    $user_id,
    $data['subject'],
    $data['message']
);

$stmt->execute();

echo json_encode([
    "status" => true,
    "message" => "Support request submitted successfully"
]);
?>
