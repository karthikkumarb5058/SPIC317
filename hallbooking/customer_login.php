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

$stmt = $conn->prepare("
    SELECT * FROM users
    WHERE (email = ? OR mobile = ?) AND role = 'customer'
");
$stmt->bind_param("ss", $input, $input);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {

    $_SESSION['customer_id'] = $user['id'];

    echo json_encode([
        "status" => true,
        "message" => "Customer login successful",
        "customer_id" => $user['id'],
        "name" => $user['full_name']
    ]);

} else {
    echo json_encode([
        "status" => false,
        "message" => "Invalid login credentials"
    ]);
}
?>
