<?php
require_once 'setup.php';

$action = $_GET['action'] ?? 'list';
$message = '';
$error = '';

// CREATE Session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $course_id = trim($_POST['course_id']);
    $group_id = trim($_POST['group_id']);
    $professor_id = trim($_POST['professor_id']);
    $session_date = $_POST['session_date'];
    
    try {
        $db = getDB();
        
        // Check duplicate
        $stmt = $db->prepare("SELECT id FROM attendance_sessions WHERE course_id=? AND group_id=? AND session_date=?");
        $stmt->execute([$course_id, $group_id, $session_date]);
        
        if ($stmt->fetch()) {
            $error = "Session already exists for this course, group, and date";
        } else {
            $stmt = $db->prepare("INSERT INTO attendance_sessions (course_id, group_id, session_date, opened_by) VALUES (?, ?, ?, ?)");
            $stmt->execute([$course_id, $group_id, $session_date, $professor_id]);
            $message = "Session created successfully! ID: " . $db->lastInsertId();
        }
    } catch (PDOException $e) {
        $error = "Error creating session";
    }
}

// CLOSE Session
if ($action === 'close' && isset($_GET['id'])) {
    try {
        $db = getDB();
        $stmt = $db->prepare("UPDATE attendance_sessions SET status='closed' WHERE id=?");
        $stmt->execute([$_GET['id']]);
        $message = "Session closed successfully!";
        $action = 'list';
    } catch (PDOException $e) {
        $error = "Error closing session";
    }
}

// Get all sessions
$db = getDB();
$sessions = $db->query("SELECT * FROM attendance_sessions ORDER BY session_date DESC, id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Attendance Sessions</title>
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

    <h1 style="text-align: center; margin-top: 20px;">Attendance Sessions</h1>

    <?php if ($message): ?>
        <div class="success"><?= $message ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <!-- Create Session Form -->
    <div class="container">
        <h2>Create New Session</h2>
        <form method="POST">
            <div class="form-group">
                <label>Course ID:</label>
                <input type="text" name="course_id" placeholder="e.g., AWP101" required>
            </div>
            
            <div class="form-group">
                <label>Group ID:</label>
                <input type="text" name="group_id" placeholder="e.g., G1" required>
            </div>
            
            <div class="form-group">
                <label>Professor ID:</label>
                <input type="text" name="professor_id" placeholder="e.g., PROF001" required>
            </div>
            
            <div class="form-group">
                <label>Session Date:</label>
                <input type="date" name="session_date" value="<?= date('Y-m-d') ?>" required>
            </div>
            
            <button type="submit" name="create">Create Session</button>
        </form>
    </div>

    <!-- Sessions List -->
    <div class="container">
        <h2>All Sessions (<?= count($sessions) ?>)</h2>
        <?php if (empty($sessions)): ?>
            <p>No sessions found. Create the first session above.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Course</th>
                        <th>Group</th>
                        <th>Date</th>
                        <th>Opened By</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sessions as $s): ?>
                    <tr>
                        <td><?= $s['id'] ?></td>
                        <td><?= htmlspecialchars($s['course_id']) ?></td>
                        <td><?= htmlspecialchars($s['group_id']) ?></td>
                        <td><?= $s['session_date'] ?></td>
                        <td><?= htmlspecialchars($s['opened_by']) ?></td>
                        <td class="status-<?= $s['status'] ?>"><?= strtoupper($s['status']) ?></td>
                        <td>
                            <?php if ($s['status'] === 'open'): ?>
                                <a href="?action=close&id=<?= $s['id'] ?>" class="btn-close" 
                                   onclick="return confirm('Close this session?')">Close</a>
                            <?php else: ?>
                                <span style="color: #999;">Closed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>