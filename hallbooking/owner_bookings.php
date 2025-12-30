<?php
require "db.php";
session_start();

if (!isset($_SESSION['partner_id'])) {
    echo json_encode([
        "status" => false,
        "message" => "Unauthorized"
    ]);
    exit;
}

$partner_id = $_SESSION['partner_id'];
$tab = $_GET['status'] ?? 'upcoming';

/* STEP 1: GET OWNER HALL ID */
$hallStmt = $conn->prepare(
    "SELECT id FROM hall_owners WHERE id = ? LIMIT 1"
);
$hallStmt->bind_param("i", $partner_id);
$hallStmt->execute();
$hallResult = $hallStmt->get_result();
$hall = $hallResult->fetch_assoc();

if (!$hall) {
    echo json_encode([
        "status" => false,
        "message" => "Hall not found for this owner"
    ]);
    exit;
}

$hall_id = $hall['id'];

/* STEP 2: BUILD BOOKINGS QUERY */
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
";

/* STATUS LOGIC */
if ($tab === 'upcoming') {
    $sql .= " AND b.booking_status = 'confirmed' AND b.booking_date >= CURDATE()";
} elseif ($tab === 'completed') {
    $sql .= " AND b.booking_status = 'confirmed' AND b.booking_date < CURDATE()";
} elseif ($tab === 'cancelled') {
    $sql .= " AND b.booking_status = 'cancelled'";
} else {
    echo json_encode(["status"=>false,"message"=>"Invalid tab"]);
    exit;
}

$sql .= " ORDER BY b.booking_date DESC";

/* STEP 3: EXECUTE */
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hall_id);
$stmt->execute();
$result = $stmt->get_result();

$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

echo json_encode([
    "status" => true,
    "current_tab" => $tab,
    "bookings" => $bookings
]);
