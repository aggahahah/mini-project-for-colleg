<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Feedback System' ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Feedback System</h1>
            <nav>
                <ul>
                    <?php if (isLoggedIn()): ?>
                        <?php if (isAdmin()): ?>
                            <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                            <li><a href="view_feedback.php">View Feedback</a></li>
                            <li><a href="manage_courses.php">Manage Courses</a></li>
                        <?php else: ?>
                            <li><a href="student_dashboard.php">Dashboard</a></li>
                            <li><a href="feedback_form.php">Submit Feedback</a></li>
                        <?php endif; ?>
                        <li><a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <!-- Error/Success messages display -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>