<?php
require "db.php";

/*
  Popular halls = halls marked with is_popular = 1
*/

$sql = "SELECT 
            id,
            hall_name,
            city,
            address,
            starting_price,
            hall_image
        FROM halls
        WHERE is_popular = 1
        ORDER BY created_at DESC";

$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        "id" => (int)$row['id'],
        "hall_name" => $row['hall_name'],
        "city" => $row['city'],
        "address" => $row['address'],
        "starting_price" => (float)$row['starting_price'],
        "hall_image" => $row['hall_image']
    ];
}

echo json_encode([
    "status" => true,
    "count" => count($data),
    "data" => $data
]);
?>
