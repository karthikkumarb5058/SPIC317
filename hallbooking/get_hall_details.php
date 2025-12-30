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
SELECT id, hall_name, area, city, capacity, rating, hall_image
FROM halls
WHERE id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hall_id);
$stmt->execute();

$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode([
    "status" => true,
    "data" => [
        "hall_id" => (int)$data['id'],
        "hall_name" => $data['hall_name'],
        "area" => $data['area'],
        "city" => $data['city'],
        "capacity" => (int)$data['capacity'],
        "rating" => (float)$data['rating'],
        "hall_image" => $data['hall_image']
    ]
]);
?>
