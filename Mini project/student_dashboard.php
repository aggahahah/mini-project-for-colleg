<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
redirectIfNotLoggedIn();

// Get student's feedback submissions
$stmt = $pdo->prepare("SELECT f.*, c.course_code, c.course_name 
                      FROM feedback f 
                      JOIN courses c ON f.course_id = c.id 
                      WHERE f.student_id = ? 
                      ORDER BY f.submission_date DESC");
$stmt->execute([$_SESSION['user_id']]);
$feedbacks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | Feedback System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Feedback System</h1>
            <nav>
                <ul>
                    <li><a href="student_dashboard.php">Dashboard</a></li>
                    <li><a href="feedback_form.php">Submit Feedback</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h2>
        
        <div class="card">
            <h3>Your Feedback Submissions</h3>
            
            <?php if (empty($feedbacks)): ?>
                <p>You haven't submitted any feedback yet.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Rating</th>
                            <th>Comments</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($feedbacks as $feedback): ?>
                            <tr>
                                <td><?= htmlspecialchars($feedback['course_code'] . ' - ' . $feedback['course_name']) ?></td>
                                <td>
                                    <?= str_repeat('★', $feedback['rating']) . str_repeat('☆', 5 - $feedback['rating']) ?>
                                </td>
                                <td><?= htmlspecialchars($feedback['comments']) ?></td>
                                <td><?= date('M d, Y', strtotime($feedback['submission_date'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>