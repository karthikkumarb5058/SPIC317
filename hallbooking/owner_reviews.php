<?php
require "db.php";
session_start();

if (!isset($_SESSION['partner_id'])) {
    echo json_encode(["status"=>false,"message"=>"Unauthorized"]);
    exit;
}

$hall_id = $_SESSION['partner_id'];

$sql = "
SELECT 
  u.full_name,
  r.rating,
  r.review,
  r.created_at
FROM reviews r
JOIN users u ON r.user_id = u.id
WHERE r.hall_id = ?
ORDER BY r.created_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hall_id);
$stmt->execute();

echo json_encode([
  "status"=>true,
  "reviews"=>$stmt->get_result()->fetch_all(MYSQLI_ASSOC)
]);
