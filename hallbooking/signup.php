<?php
require "db.php";

$data = json_decode(file_get_contents("php://input"), true);

/* Required fields */
$required = ['role','full_name','mobile','email','city','password','confirm_password'];

foreach ($required as $field) {
    if (empty($data[$field])) {
        echo json_encode(["status"=>false,"message"=>"$field is required"]);
        exit;
    }
}

/* Full name validation */
$full_name = trim($data['full_name']);

if (strlen($full_name) < 3) {
    echo json_encode([
        "status" => false,
        "message" => "Full name must be at least 3 characters"
    ]);
    exit;
}

if (!preg_match("/^[a-zA-Z ]+$/", $full_name)) {
    echo json_encode([
        "status" => false,
        "message" => "Full name must contain only letters and spaces"
    ]);
    exit;
}

/* Password match */
if ($data['password'] !== $data['confirm_password']) {
    echo json_encode(["status"=>false,"message"=>"Passwords do not match"]);
    exit;
}

/* Email validation */
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status"=>false,"message"=>"Invalid email format"]);
    exit;
}

/* Mobile validation */
if (!preg_match("/^[0-9]{10}$/", $data['mobile'])) {
    echo json_encode(["status"=>false,"message"=>"Invalid mobile number"]);
    exit;
}

/* Prepare values */
$role = $data['role'];
$mobile = $data['mobile'];
$email = $data['email'];
$city = $data['city'];
$password = password_hash($data['password'], PASSWORD_DEFAULT);

/* Insert */
$stmt = $conn->prepare(
    "INSERT INTO users (role, full_name, mobile, email, city, password)
     VALUES (?,?,?,?,?,?)"
);

$stmt->bind_param(
    "ssssss",
    $role,
    $full_name,
    $mobile,
    $email,
    $city,
    $password
);

if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Signup successful"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Email or Mobile already exists"
    ]);
}
?>
