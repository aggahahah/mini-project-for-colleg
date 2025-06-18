<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
redirectIfNotAdmin();

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_course'])) {
        // Add new course
        $course_code = trim($_POST['course_code']);
        $course_name = trim($_POST['course_name']);
        $instructor_name = trim($_POST['instructor_name']);
        
        if (empty($course_code) || empty($course_name) || empty($instructor_name)) {
            $error = 'All fields are required';
        } else {
            $stmt = $pdo->prepare("INSERT INTO courses (course_code, course_name, instructor_name) VALUES (?, ?, ?)");
            
            if ($stmt->execute([$course_code, $course_name, $instructor_name])) {
                $success = 'Course added successfully';
            } else {
                $error = 'Failed to add course. Course code might already exist.';
            }
        }
    } elseif (isset($_POST['delete_course'])) {
        // Delete course
        $course_id = $_POST['course_id'];
        
        $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
        if ($stmt->execute([$course_id])) {
            $success = 'Course deleted successfully';
        } else {
            $error = 'Failed to delete course';
        }
    }
}

// Get all courses
$courses = $pdo->query("SELECT * FROM courses ORDER BY course_name")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses | Feedback System</title>
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
        <h2>Manage Courses</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        
        <div class="card">
            <h3>Add New Course</h3>
            <form method="POST">
                <div class="form-group">
                    <label for="course_code">Course Code</label>
                    <input type="text" id="course_code" name="course_code" required>
                </div>
                
                <div class="form-group">
                    <label for="course_name">Course Name</label>
                    <input type="text" id="course_name" name="course_name" required>
                </div>
                
                <div class="form-group">
                    <label for="instructor_name">Instructor Name</label>
                    <input type="text" id="instructor_name" name="instructor_name" required>
                </div>
                
                <button type="submit" name="add_course" class="btn">Add Course</button>
            </form>
        </div>
        
        <div class="card">
            <h3>Existing Courses</h3>
            
            <?php if (empty($courses)): ?>
                <p>No courses found.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Instructor</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><?= htmlspecialchars($course['course_code']) ?></td>
                                <td><?= htmlspecialchars($course['course_name']) ?></td>
                                <td><?= htmlspecialchars($course['instructor_name']) ?></td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                        <button type="submit" name="delete_course" class="btn btn-danger" 
                                                onclick="return confirm('Are you sure you want to delete this course?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>