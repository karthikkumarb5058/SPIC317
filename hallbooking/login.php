<?php
require "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['email_or_mobile']) || empty($data['password'])) {
    echo json_encode(["status"=>false,"message"=>"All fields required"]);
    exit;
}

$input = $data['email_or_mobile'];
$password = $data['password'];

/* Find user */
$stmt = $conn->prepare(
    "SELECT * FROM users WHERE email=? OR mobile=?"
);
$stmt->bind_param("ss", $input, $input);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    echo json_encode([
        "status"=>true,
        "message"=>"Login successful",
        "user_id"=>$user['id'],
        "role"=>$user['role'],
        "name"=>$user['full_name']
    ]);
} else {
    echo json_encode(["status"=>false,"message"=>"Invalid login credentials"]);
}
?>
