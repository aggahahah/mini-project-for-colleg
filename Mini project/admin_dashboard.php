<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
redirectIfNotAdmin();

// Get feedback statistics
$total_feedback = $pdo->query("SELECT COUNT(*) FROM feedback")->fetchColumn();
$total_courses = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();
$total_students = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();

// Get recent feedback
$stmt = $pdo->query("SELECT f.*, c.course_code, c.course_name, u.username 
                    FROM feedback f 
                    JOIN courses c ON f.course_id = c.id 
                    JOIN users u ON f.student_id = u.id 
                    ORDER BY f.submission_date DESC 
                    LIMIT 5");
$recent_feedback = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Feedback System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Feedback System - Admin</h1>
            <nav>
                <ul>
                    <li><a href="admin_dashboard.php">Dashboard</a></li>
                    <li><a href="view_feedback.php">View Feedback</a></li>
                    <li><a href="manage_courses.php">Manage Courses</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2>Admin Dashboard</h2>
        
        <div class="card">
            <h3>Statistics</h3>
            <div style="display: flex; justify-content: space-between;">
                <div class="card" style="width: 30%; text-align: center;">
                    <h4>Total Feedback</h4>
                    <p style="font-size: 2em;"><?= $total_feedback ?></p>
                </div>
                <div class="card" style="width: 30%; text-align: center;">
                    <h4>Total Courses</h4>
                    <p style="font-size: 2em;"><?= $total_courses ?></p>
                </div>
                <div class="card" style="width: 30%; text-align: center;">
                    <h4>Total Students</h4>
                    <p style="font-size: 2em;"><?= $total_students ?></p>
                </div>
            </div>
        </div>
        
        <div class="card">
            <h3>Recent Feedback</h3>
            
            <?php if (empty($recent_feedback)): ?>
                <p>No feedback submitted yet.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Rating</th>
                            <th>Comments</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_feedback as $feedback): ?>
                            <tr>
                                <td><?= htmlspecialchars($feedback['username']) ?></td>
                                <td><?= htmlspecialchars($feedback['course_code']) ?></td>
                                <td><?= str_repeat('★', $feedback['rating']) . str_repeat('☆', 5 - $feedback['rating']) ?></td>
                                <td><?= htmlspecialchars($feedback['comments']) ?></td>
                                <td><?= date('M d, Y', strtotime($feedback['submission_date'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p style="text-align: right; margin-top: 10px;">
                    <a href="view_feedback.php" class="btn">View All Feedback</a>
                </p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>