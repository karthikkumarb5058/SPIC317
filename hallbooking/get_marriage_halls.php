<?php
require "db.php";

/* Get city from query */
$city = $_GET['city'] ?? '';

if ($city == '') {
    echo json_encode([
        "status" => false,
        "message" => "City is required"
    ]);
    exit;
}

/* Query marriage halls */
$stmt = $conn->prepare("
    SELECT 
        id,
        hall_name,
        area,
        city,
        capacity,
        starting_price,
        rating,
        hall_image
    FROM halls
    WHERE hall_type = 'marriage'
      AND city = ?
");

$stmt->bind_param("s", $city);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        "hall_id" => (int)$row['id'],
        "hall_name" => $row['hall_name'],
        "location" => $row['area'] . ', ' . $row['city'],
        "capacity" => (int)$row['capacity'],
        "rating" => (float)$row['rating'],
        "price_per_day" => (float)$row['starting_price'],
        "hall_image" => $row['hall_image']
    ];
}

echo json_encode([
    "status" => true,
    "count" => count($data),
    "data" => $data
]);
?>
