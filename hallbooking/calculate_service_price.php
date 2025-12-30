<?php
require "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['service_ids'])) {
    echo json_encode([
        "status" => false,
        "message" => "Service IDs required"
    ]);
    exit;
}

$ids = implode(',', array_map('intval', $data['service_ids']));

$sql = "SELECT SUM(service_price) AS total FROM hall_services WHERE id IN ($ids)";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

echo json_encode([
    "status" => true,
    "total_amount" => (float)$row['total']
]);
?>
