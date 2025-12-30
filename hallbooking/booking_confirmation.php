<?php
require "db.php";

$booking_id = $_GET['booking_id'] ?? '';

if ($booking_id == '') {
    echo json_encode([
        "status"=>false,
        "message"=>"Booking ID required"
    ]);
    exit;
}

$stmt = $conn->prepare("
    SELECT
        b.id AS booking_id,
        h.hall_name,
        h.city,
        h.area,
        p.package_name,
        b.booking_date,
        b.total_amount,
        pay.payment_method,
        pay.transaction_id,
        pay.paid_at
    FROM bookings b
    JOIN halls h ON b.hall_id = h.id
    JOIN hall_packages p ON b.package_id = p.id
    JOIN payments pay ON pay.booking_id = b.id
    WHERE b.id = ?
");

$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo json_encode([
        "status"=>false,
        "message"=>"Booking not found"
    ]);
    exit;
}

echo json_encode([
    "status"=>true,
    "data"=>[
        "booking_id"=>$row['booking_id'],
        "hall_name"=>$row['hall_name'],
        "location"=>$row['area'].", ".$row['city'],
        "package"=>$row['package_name'],
        "booking_date"=>$row['booking_date'],
        "total_paid"=>$row['total_amount'],
        "payment_method"=>$row['payment_method'],
        "transaction_id"=>$row['transaction_id'],
        "paid_at"=>$row['paid_at']
    ]
]);
?>
