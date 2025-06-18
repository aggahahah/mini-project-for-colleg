<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (isLoggedIn()) {
    if (isAdmin()) {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: student_dashboard.php");
    }
    exit();
} else {
    header("Location: login.php");
    exit();
}
?>