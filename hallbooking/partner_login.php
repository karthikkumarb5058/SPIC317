<?php
require "db.php";
session_start();

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['email_or_mobile']) || empty($data['password'])) {
    echo json_encode([
        "status" => false,
        "message" => "All fields required"
    ]);
    exit;
}

$input = $data['email_or_mobile'];
$password = $data['password'];

$stmt = $conn->prepare(
    "SELECT * FROM hall_owners WHERE email = ? OR mobile = ?"
);
$stmt->bind_param("ss", $input, $input);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {

    /* ✅ CREATE SESSION (THIS FIXES UNAUTHORIZED ISSUE) */
    $_SESSION['partner_id'] = $user['id'];
    $_SESSION['partner_name'] = $user['full_name'];

    echo json_encode([
        "status" => true,
        "message" => "Login successful",
        "partner_id" => $user['id'],
        "hall_name" => $user['hall_name']
    ]);

} else {
    echo json_encode([
        "status" => false,
        "message" => "Invalid login credentials"
    ]);
}
?>
