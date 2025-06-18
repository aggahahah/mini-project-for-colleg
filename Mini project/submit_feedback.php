<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
redirectIfNotLoggedIn();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: feedback_form.php");
    exit();
}

try {
    // Validate input
    $required = ['student_id', 'course_id', 'rating'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }

    $student_id = (int)$_POST['student_id'];
    $course_id = (int)$_POST['course_id'];
    $rating = (int)$_POST['rating'];
    $comments = !empty($_POST['comments']) ? trim($_POST['comments']) : null;

    // Validate rating range
    if ($rating < 1 || $rating > 5) {
        throw new Exception("Invalid rating value");
    }

    // Check if feedback already exists
    $stmt = $pdo->prepare("SELECT id FROM feedback WHERE student_id = ? AND course_id = ?");
    $stmt->execute([$student_id, $course_id]);
    
    if ($stmt->rowCount() > 0) {
        throw new Exception("You have already submitted feedback for this course");
    }

    // Insert feedback
    $stmt = $pdo->prepare("
        INSERT INTO feedback (student_id, course_id, rating, comments)
        VALUES (?, ?, ?, ?)
    ");
    
    if ($stmt->execute([$student_id, $course_id, $rating, $comments])) {
        header("Location: feedback_form.php?success=Feedback+submitted+successfully");
    } else {
        throw new Exception("Failed to submit feedback");
    }
} catch (Exception $e) {
    error_log("Feedback submission error: " . $e->getMessage());
    header("Location: feedback_form.php?error=" . urlencode($e->getMessage()));
    exit();
}