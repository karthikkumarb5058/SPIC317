<?php
require "db.php";

$data = json_decode(file_get_contents("php://input"), true);

/* Required fields */
$required = [
    'full_name','mobile','email','password','confirm_password',
    'hall_name','hall_type','address','capacity','starting_price'
];

foreach ($required as $field) {
    if (empty($data[$field])) {
        echo json_encode(["status"=>false,"message"=>"$field is required"]);
        exit;
    }
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

/* Password match */
if ($data['password'] !== $data['confirm_password']) {
    echo json_encode(["status"=>false,"message"=>"Passwords do not match"]);
    exit;
}

/* Capacity & price */
if ($data['capacity'] <= 0 || $data['starting_price'] <= 0) {
    echo json_encode(["status"=>false,"message"=>"Capacity and price must be greater than zero"]);
    exit;
}

/* Hash password */
$password = password_hash($data['password'], PASSWORD_DEFAULT);

/* Insert */
$stmt = $conn->prepare("
    INSERT INTO hall_owners
    (full_name,mobile,email,password,hall_name,hall_type,address,capacity,starting_price,hall_photo)
    VALUES (?,?,?,?,?,?,?,?,?,?)
");

$photo = $data['hall_photo'] ?? null;

$stmt->bind_param(
    "sssssssdss",
    $data['full_name'],
    $data['mobile'],
    $data['email'],
    $password,
    $data['hall_name'],
    $data['hall_type'],
    $data['address'],
    $data['capacity'],
    $data['starting_price'],
    $photo
);

if ($stmt->execute()) {
    echo json_encode(["status"=>true,"message"=>"Partner signup successful"]);
} else {
    echo json_encode(["status"=>false,"message"=>"Email or Mobile already exists"]);
}
?>
