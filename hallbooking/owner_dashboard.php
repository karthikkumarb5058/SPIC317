<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");
require_once "db.php";
session_start();

/* Check owner login */
if (!isset($_SESSION['partner_id'])) {
    echo json_encode([
        "status" => false,
        "message" => "Unauthorized"
    ]);
    exit;
}

/* In your system: partner_id == hall_id */
$hall_id = $_SESSION['partner_id'];

/* CORRECT SQL (NO WRONG COLUMNS) */
$sql = "
SELECT 
    b.id AS booking_id,
    u.full_name AS customer_name,
    b.booking_date,
    b.total_guests,
    b.total_amount,
    b.booking_status
FROM bookings b
JOIN users u ON b.user_id = u.id
WHERE b.hall_id = ?
ORDER BY b.booking_date DESC
";

$stmt = $conn->prepare($sql);

/* Safety check */
if (!$stmt) {
    echo json_encode([
        "status" => false,
        "message" => "Query preparation failed",
        "error" => $conn->error
    ]);
    exit;
}

$stmt->bind_param("i", $hall_id);
$stmt->execute();

$result = $stmt->get_result();
$bookings = [];

while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

echo json_encode([
    "status" => true,
    "bookings" => $bookings
]);

$stmt->close();
$conn->close();
