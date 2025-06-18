<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
redirectIfNotLoggedIn();

if (isAdmin()) {
    header("Location: admin_dashboard.php");
    exit();
}

// Fetch courses for dropdown
try {
    $courses = $pdo->query("SELECT id, course_code, course_name FROM courses ORDER BY course_name")->fetchAll();
} catch (PDOException $e) {
    die("Error loading courses: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback | Feedback System</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .rating {
            direction: rtl;
            unicode-bidi: bidi-override;
            display: flex;
            gap: 5px;
        }
        .rating input {
            display: none;
        }
        .rating label {
            font-size: 24px;
            color: #ccc;
            cursor: pointer;
        }
        .rating input:checked ~ label,
        .rating label:hover,
        .rating label:hover ~ label {
            color: #f5b301;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h2>Submit Course Feedback</h2>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>

        <div class="card">
            <form id="feedbackForm" action="submit_feedback.php" method="POST">
                <input type="hidden" name="student_id" value="<?= $_SESSION['user_id'] ?>">

                <div class="form-group">
                    <label for="course_id">Select Course:</label>
                    <select id="course_id" name="course_id" required>
                        <option value="">-- Select Course --</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['id'] ?>">
                                <?= htmlspecialchars($course['course_code'] . ' - ' . $course['course_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Rating:</label>
                    <div class="rating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" required>
                            <label for="star<?= $i ?>">★</label>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="comments">Comments:</label>
                    <textarea id="comments" name="comments" rows="4" placeholder="Write any feedback..."></textarea>
                </div>

                <button type="submit" class="btn">Submit Feedback</button>
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
