<?php
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: /PHP8/HR_SYSTEM/view/Login/login.php');
exit;
