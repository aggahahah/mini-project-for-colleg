<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode([]);
    exit();
}

$stmt = $pdo->query("SELECT id, course_code, course_name, instructor_name FROM courses ORDER BY course_name");
$courses = $stmt->fetchAll();

echo json_encode($courses);
?>