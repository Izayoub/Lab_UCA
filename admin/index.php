<?php
// Include configuration file
require_once '../includes/config.php';

// Redirect to login if not logged in
require_login();

// Redirect to dashboard
header('Location: dashboard.php');
exit;
?>
