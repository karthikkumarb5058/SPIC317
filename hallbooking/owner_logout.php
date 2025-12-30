<?php
session_start();

/* Remove only owner session */
unset($_SESSION['partner_id']);

/* Optional: destroy full session if owner-only app */
session_destroy();

echo json_encode([
    "status" => true,
    "message" => "Owner logged out successfully"
]);
