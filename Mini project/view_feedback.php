<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
redirectIfNotAdmin();

// Get all feedback with filters
$course_filter = $_GET['course_id'] ?? '';
$rating_filter = $_GET['rating'] ?? '';

$query = "SELECT f.*, c.course_code, c.course_name, u.username 
          FROM feedback f 
          JOIN courses c ON f.course_id = c.id 
          JOIN users u ON f.student_id = u.id 
          WHERE 1=1";

$params = [];

if (!empty($course_filter)) {
    $query .= " AND f.course_id = ?";
    $params[] = $course_filter;
}

if (!empty($rating_filter)) {
    $query .= " AND f.rating = ?";
    $params[] = $rating_filter;
}

$query .= " ORDER BY f.submission_date DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$feedbacks = $stmt->fetchAll();

// Get courses for filter dropdown
$courses = $pdo->query("SELECT id, course_code, course_name FROM courses ORDER BY course_name")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback | Feedback System</title>
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
        <h2>View Feedback</h2>
        
        <div class="card">
            <h3>Filters</h3>
            <form method="GET" style="display: flex; gap: 15px; align-items: flex-end;">
                <div class="form-group" style="flex: 1;">
                    <label for="course_id">Course</label>
                    <select id="course_id" name="course_id">
                        <option value="">All Courses</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['id'] ?>" <?= $course_filter == $course['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($course['course_code'] . ' - ' . $course['course_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group" style="flex: 1;">
                    <label for="rating">Rating</label>
                    <select id="rating" name="rating">
                        <option value="">All Ratings</option>
                        <option value="1" <?= $rating_filter === '1' ? 'selected' : '' ?>>1 Star</option>
                        <option value="2" <?= $rating_filter === '2' ? 'selected' : '' ?>>2 Stars</option>
                        <option value="3" <?= $rating_filter === '3' ? 'selected' : '' ?>>3 Stars</option>
                        <option value="4" <?= $rating_filter === '4' ? 'selected' : '' ?>>4 Stars</option>
                        <option value="5" <?= $rating_filter === '5' ? 'selected' : '' ?>>5 Stars</option>
                    </select>
                </div>
                
                <button type="submit" class="btn">Apply Filters</button>
                <?php if (!empty($course_filter) || !empty($rating_filter)): ?>
                    <a href="view_feedback.php" class="btn btn-danger">Clear Filters</a>
                <?php endif; ?>
            </form>
        </div>
        
        <div class="card">
            <h3>Feedback Records</h3>
            
            <?php if (empty($feedbacks)): ?>
                <p>No feedback found with the selected filters.</p>
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
                        <?php foreach ($feedbacks as $feedback): ?>
                            <tr>
                                <td><?= htmlspecialchars($feedback['username']) ?></td>
                                <td><?= htmlspecialchars($feedback['course_code'] . ' - ' . $feedback['course_name']) ?></td>
                                <td><?= str_repeat('★', $feedback['rating']) . str_repeat('☆', 5 - $feedback['rating']) ?></td>
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