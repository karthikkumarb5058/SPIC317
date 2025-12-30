<?php
header("Content-Type: application/json");
require "db.php";

/* Get city from URL */
$city = $_GET['city'] ?? '';

if (empty($city)) {
    echo json_encode([
        "status" => false,
        "message" => "City is required"
    ]);
    exit;
}

/* SQL to fetch halls by city */
$sql = "
SELECT 
    id,
    hall_name,
    hall_type,
    city,
    area,
    capacity,
    starting_price,
    rating,
    hall_image
FROM halls
WHERE city = ?
ORDER BY rating DESC
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "status" => false,
        "message" => "Query preparation failed",
        "error" => $conn->error
    ]);
    exit;
}

$stmt->bind_param("s", $city);
$stmt->execute();

$result = $stmt->get_result();
$halls = [];

while ($row = $result->fetch_assoc()) {
    $halls[] = $row;
}

echo json_encode([
    "status" => true,
    "city" => $city,
    "halls" => $halls
]);

$stmt->close();
$conn->close();
