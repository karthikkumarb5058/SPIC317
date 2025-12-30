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
SELECT id, package_name, price
FROM hall_packages
WHERE hall_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hall_id);
$stmt->execute();

$result = $stmt->get_result();
$packages = [];

while ($pkg = $result->fetch_assoc()) {

    $services = [];
    $svc = $conn->prepare("
        SELECT service_name
        FROM package_services
        WHERE package_id = ?
    ");
    $svc->bind_param("i", $pkg['id']);
    $svc->execute();
    $svcRes = $svc->get_result();

    while ($s = $svcRes->fetch_assoc()) {
        $services[] = $s['service_name'];
    }

    $packages[] = [
        "package_id" => (int)$pkg['id'],
        "package_name" => $pkg['package_name'],
        "price" => (float)$pkg['price'],
        "services" => $services
    ];
}

echo json_encode([
    "status" => true,
    "data" => $packages
]);
?>
