<?php
require "db.php";

$hall_id = $_GET['hall_id'] ?? 0;

if ($hall_id == 0) {
    echo json_encode([
        "status" => false,
        "message" => "hall_id is required"
    ]);
    exit;
}

$sql = "
SELECT id, service_name, service_price
FROM hall_services
WHERE hall_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hall_id);
$stmt->execute();

$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        "service_id" => (int)$row['id'],
        "service_name" => $row['service_name'],
        "price" => (float)$row['service_price']
    ];
}

echo json_encode([
    "status" => true,
    "total_services" => count($data),
    "data" => $data
]);
?>
