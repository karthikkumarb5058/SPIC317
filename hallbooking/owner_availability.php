<?php
require "db.php";
session_start();

if (!isset($_SESSION['partner_id'])) {
    echo json_encode(["status"=>false,"message"=>"Unauthorized"]);
    exit;
}

$hall_id = $_SESSION['partner_id'];

/* Upcoming bookings */
$bookings = $conn->query("
SELECT booking_date, 'booked' AS type
FROM bookings
WHERE hall_id=$hall_id AND booking_status='confirmed'
");

/* Blocked dates */
$blocked = $conn->query("
SELECT start_date, end_date, reason
FROM blocked_dates
WHERE hall_id=$hall_id
");

$data = [
  "booked_dates"=>$bookings->fetch_all(MYSQLI_ASSOC),
  "blocked_dates"=>$blocked->fetch_all(MYSQLI_ASSOC)
];

echo json_encode(["status"=>true,"availability"=>$data]);
