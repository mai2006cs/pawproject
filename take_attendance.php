<?php

$dataFile = __DIR__ . "/data/students.json";

if (!file_exists($dataFile)) {
    echo "No students found.";
    exit;
}

$students = json_decode(file_get_contents($dataFile), true);

// Filter out invalid students (those without proper data)
$students = array_filter($students, function($s) {
    return !empty($s['student_id']) && 
           (!empty($s['last_name']) || !empty($s['first_name']));
});

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $date = date("Y-m-d");
    $filename = __DIR__ . "/data/attendance_$date.json";

    if (file_exists($filename)) {
        $message = "⚠️ Attendance already taken for today!";
    } else {
        $attendance = [];

        foreach ($_POST['status'] as $id => $state) {
            $attendance[] = [
                "student_id" => $id,
                "status" => $state
            ];
        }

        file_put_contents($filename, json_encode($attendance, JSON_PRETTY_PRINT));
        $message = "✅ Attendance saved successfully!";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Take Attendance</title>
    <link rel="stylesheet" href="style.css?v=6">
</head>
<body>
    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="index.php">Attendance List</a>
        <a href="take_attendance.php">Take Attendance</a>
        <a href="manage_students.php">Manage Students</a>
        <a href="sessions.php">Sessions</a>
    </nav>

    <div class="container">
        <h2>📋 Take Attendance</h2>
        
        <div class="back-link">
            <a href="index.php">← Back to Home</a>
        </div>

        <?php if (isset($message)): ?>
            <div class="message <?= strpos($message, '✅') !== false ? 'success' : 'warning' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <?php if (empty($students)): ?>
            <p style="color: #666;">No students found. <a href="index.php">Add students first</a>.</p>
        <?php else: ?>
            
            <div class="student-count">Total Students: <?= count($students) ?></div>

            <form method="post">
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th style="text-align: center;">Present</th>
                            <th style="text-align: center;">Absent</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $s): ?>
                        <tr>
                            <td><?= htmlspecialchars($s["student_id"]) ?></td>
                            <td>
                                <?php 
                                $name = trim(($s["last_name"] ?? '') . " " . ($s["first_name"] ?? ''));
                                echo htmlspecialchars($name ?: 'Unknown');
                                ?>
                            </td>
                            <td style="text-align: center;">
                                <input type="radio" name="status[<?= $s['student_id'] ?>]" value="present" checked>
                            </td>
                            <td style="text-align: center;">
                                <input type="radio" name="status[<?= $s['student_id'] ?>]" value="absent">
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <button type="submit">💾 Save Attendance</button>
            </form>

        <?php endif; ?>
    </div>
</body>
</html>
