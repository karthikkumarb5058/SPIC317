<?php
require "db.php";

$type = $_GET['type'] ?? '';
$city = $_GET['city'] ?? '';

if ($type === '' || $city === '') {
    echo json_encode([
        "status" => false,
        "message" => "type and city are required"
    ]);
    exit;
}

$sql = "
SELECT id, hall_name, area, city, starting_price, rating, hall_image
FROM halls
WHERE hall_type = ? AND city = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $type, $city);
$stmt->execute();

$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        "hall_id" => (int)$row['id'],
        "hall_name" => $row['hall_name'],
        "area" => $row['area'],
        "city" => $row['city'],
        "starting_price" => (float)$row['starting_price'],
        "rating" => (float)$row['rating'],
        "hall_image" => $row['hall_image']
    ];
}

echo json_encode([
    "status" => true,
    "count" => count($data),
    "data" => $data
]);
?>
