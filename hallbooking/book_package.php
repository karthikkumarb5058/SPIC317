<?php
require "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$required = [
    'user_id','hall_id','package_id',
    'booking_date','total_amount',
    'payment_method','transaction_id'
];

foreach ($required as $field) {
    if (!isset($data[$field]) || $data[$field] === '') {
        echo json_encode([
            "status" => false,
            "message" => "$field is required"
        ]);
        exit;
    }
}

/* INSERT INTO BOOKINGS */
$sql = "INSERT INTO bookings 
(user_id, hall_id, package_id, booking_date, total_amount)
VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "status" => false,
        "error" => "Prepare failed",
        "mysql_error" => $conn->error
    ]);
    exit;
}

$stmt->bind_param(
    "iiisd",
    $data['user_id'],
    $data['hall_id'],
    $data['package_id'],
    $data['booking_date'],
    $data['total_amount']
);

$stmt->execute();
$booking_id = $stmt->insert_id;

/* INSERT INTO PAYMENTS */
$sql2 = "INSERT INTO payments
(booking_id, payment_method, transaction_id, paid_amount)
VALUES (?, ?, ?, ?)";

$stmt2 = $conn->prepare($sql2);

if (!$stmt2) {
    echo json_encode([
        "status" => false,
        "error" => "Payment prepare failed",
        "mysql_error" => $conn->error
    ]);
    exit;
}

$stmt2->bind_param(
    "issd",
    $booking_id,
    $data['payment_method'],
    $data['transaction_id'],
    $data['total_amount']
);

$stmt2->execute();

echo json_encode([
    "status" => true,
    "message" => "Booking confirmed",
    "booking_id" => $booking_id
]);
?>
