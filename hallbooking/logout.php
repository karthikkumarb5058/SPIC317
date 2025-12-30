<?php
session_start();

/* Clear all session variables */
$_SESSION = [];

/* Destroy the session */
session_destroy();

/* Optional: destroy session cookie */
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

/* Send response */
echo json_encode([
    "status" => true,
    "message" => "Logout successful",
    "redirect_to" => "role_selection"
]);
?>
