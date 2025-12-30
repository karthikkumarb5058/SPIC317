<?php
require "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$user_id = 1;

if (
    empty($data['full_name']) ||
    empty($data['mobile']) ||
    empty($data['city'])
) {
    echo json_encode([
        "status" => false,
        "message" => "All fields are required"
    ]);
    exit;
}

$stmt = $conn->prepare("
    UPDATE users
    SET full_name = ?, mobile = ?, city = ?
    WHERE id = ?
");
$stmt->bind_param(
    "sssi",
    $data['full_name'],
    $data['mobile'],
    $data['city'],
    $user_id
);

$stmt->execute();

echo json_encode([
    "status" => true,
    "message" => "Profile updated successfully"
]);
?>
