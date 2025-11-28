<?php
// Tutorial 3 Exercise 1 - Enhanced add_student.php
header('Content-Type: application/json');

// Get and validate input
$student_id = trim($_POST['student_id'] ?? '');
$last_name  = trim($_POST['last_name'] ?? '');
$first_name = trim($_POST['first_name'] ?? '');
$email      = trim($_POST['email'] ?? '');

// Validation
$errors = [];

if (empty($student_id)) {
    $errors[] = "Student ID is required";
} elseif (!preg_match('/^\d+$/', $student_id)) {
    $errors[] = "Student ID must contain numbers only";
}

if (empty($last_name)) {
    $errors[] = "Last name is required";
} elseif (!preg_match('/^[a-zA-Z\s]+$/', $last_name)) {
    $errors[] = "Last name must contain letters only";
}

if (empty($first_name)) {
    $errors[] = "First name is required";
} elseif (!preg_match('/^[a-zA-Z\s]+$/', $first_name)) {
    $errors[] = "First name must contain letters only";
}

if (empty($email)) {
    $errors[] = "Email is required";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Email must be in valid format";
}

// If validation fails, return errors
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(["errors" => $errors]);
    exit;
}

$file = __DIR__ . "/data/students.json";

$students = [];

// Load existing students
if (file_exists($file)) {
    $content = file_get_contents($file);
    $students = json_decode($content, true);
    
    // Check if file was corrupted
    if (!is_array($students)) {
        $students = [];
    }
}

// Check for duplicate student ID
foreach ($students as $student) {
    if ($student['student_id'] === $student_id) {
        http_response_code(400);
        echo json_encode(["errors" => ["Student ID already exists"]]);
        exit;
    }
}

// Add new record
$students[] = [
    "student_id" => $student_id,
    "last_name"  => $last_name,
    "first_name" => $first_name,
    "email"      => $email
];

// Save back to file
file_put_contents($file, json_encode($students, JSON_PRETTY_PRINT));

echo json_encode(["message" => "Student added successfully"]);
?>
