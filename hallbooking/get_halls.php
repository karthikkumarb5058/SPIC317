<?php
require "db.php";

$city = $_GET['city'] ?? '';
$type = $_GET['type'] ?? '';

$sql = "SELECT id,hall_name,city,address,starting_price,hall_image 
        FROM halls WHERE 1=1";

if ($city != '') {
    $sql .= " AND city='$city'";
}

if ($type != '') {
    $sql .= " AND hall_type='$type'";
}

$res = $conn->query($sql);

$data = [];
while ($row = $res->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
