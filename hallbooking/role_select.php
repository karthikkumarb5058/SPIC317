<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['role'])) {
    echo json_encode(["status"=>false,"message"=>"Role is required"]);
    exit;
}

$role = $data['role'];

if (!in_array($role, ['customer','hall_owner'])) {
    echo json_encode(["status"=>false,"message"=>"Invalid role"]);
    exit;
}

echo json_encode([
    "status" => true,
    "message" => "Role selected",
    "role" => $role
]);
?>
