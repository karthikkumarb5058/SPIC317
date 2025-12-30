<?php
require "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$required = ['user_id','hall_id','service_ids','total_amount','booking_date'];

foreach ($required as $field) {
    if (empty($data[$field])) {
        echo json_encode([
            "status"=>false,
            "message"=>"$field is required"
        ]);
        exit;
    }
}

$service_ids = implode(',', array_map('intval', $data['service_ids']));

$stmt = $conn->prepare("
    INSERT INTO service_bookings
    (user_id, hall_id, service_ids, total_amount, booking_date)
    VALUES (?,?,?,?,?)
");

$stmt->bind_param(
    "iisds",
    $data['user_id'],
    $data['hall_id'],
    $service_ids,
    $data['total_amount'],
    $data['booking_date']
);

if ($stmt->execute()) {
    echo json_encode([
        "status"=>true,
        "message"=>"Services booked successfully"
    ]);
} else {
    echo json_encode([
        "status"=>false,
        "message"=>"Booking failed"
    ]);
}
?>
