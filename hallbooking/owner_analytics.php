<?php
require "db.php";
session_start();

if (!isset($_SESSION['partner_id'])) {
    echo json_encode(["status"=>false,"message"=>"Unauthorized"]);
    exit;
}

$hall_id = $_SESSION['partner_id'];

/* Total Earnings */
$earn = $conn->query("
SELECT IFNULL(SUM(total_amount),0) AS total
FROM bookings
WHERE hall_id=$hall_id AND booking_status='confirmed'
")->fetch_assoc();

/* Total Bookings */
$bookings = $conn->query("
SELECT COUNT(*) AS total
FROM bookings
WHERE hall_id=$hall_id
")->fetch_assoc();

/* Avg Rating */
$rating = $conn->query("
SELECT IFNULL(AVG(rating),0) AS avg_rating
FROM reviews
WHERE hall_id=$hall_id
")->fetch_assoc();

echo json_encode([
  "status"=>true,
  "analytics"=>[
    "total_earnings"=>$earn['total'],
    "total_bookings"=>$bookings['total'],
    "avg_rating"=>round($rating['avg_rating'],1)
  ]
]);
