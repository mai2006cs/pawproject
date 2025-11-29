<?php
// ============================================
// STUDENT MANAGEMENT (CRUD Operations)
// All-in-one file for managing students
// ============================================

require_once 'setup.php';

$action = $_GET['action'] ?? 'list';
$message = '';
$error = '';

// CREATE - Add Student
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $fullname = trim($_POST['fullname']);
    $matricule = trim($_POST['matricule']);
    $group_id = trim($_POST['group_id']);
    
    if (empty($fullname) || empty($matricule) || empty($group_id)) {
        $error = "All fields are required";
    } else {
        try {
            $db = getDB();
            $stmt = $db->prepare("INSERT INTO students (fullname, matricule, group_id) VALUES (?, ?, ?)");
            $stmt->execute([$fullname, $matricule, $group_id]);
            $message = "Student added successfully!";
        } catch (PDOException $e) {
            $error = "Error: Matricule may already exist";
        }
    }
}

// UPDATE - Edit Student
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $fullname = trim($_POST['fullname']);
    $matricule = trim($_POST['matricule']);
    $group_id = trim($_POST['group_id']);
    
    try {
        $db = getDB();
        $stmt = $db->prepare("UPDATE students SET fullname=?, matricule=?, group_id=? WHERE id=?");
        $stmt->execute([$fullname, $matricule, $group_id, $id]);
        $message = "Student updated successfully!";
        $action = 'list';
    } catch (PDOException $e) {
        $error = "Error updating student";
    }
}

// DELETE - Remove Student
if ($action === 'delete' && isset($_GET['id'])) {
    try {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM students WHERE id=?");
        $stmt->execute([$_GET['id']]);
        $message = "Student deleted successfully!";
        $action = 'list';
    } catch (PDOException $e) {
        $error = "Error deleting student";
    }
}

// Get student for editing
$editStudent = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM students WHERE id=?");
    $stmt->execute([$_GET['id']]);
    $editStudent = $stmt->fetch();
}

// Get all students
$db = getDB();
$students = $db->query("SELECT * FROM students ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Students</title>
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

    <div style="padding: 20px;">
    <h1>Student Management</h1>

    <?php if ($message): ?>
        <div class="success"><?= $message ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <!-- Add/Edit Form -->
    <div class="container">
        <h2><?= $editStudent ? 'Edit Student' : 'Add New Student' ?></h2>
        <form method="POST">
            <?php if ($editStudent): ?>
                <input type="hidden" name="id" value="<?= $editStudent['id'] ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="fullname" value="<?= $editStudent['fullname'] ?? '' ?>" required>
            </div>
            
            <div class="form-group">
                <label>Matricule:</label>
                <input type="text" name="matricule" value="<?= $editStudent['matricule'] ?? '' ?>" required>
            </div>
            
            <div class="form-group">
                <label>Group ID:</label>
                <input type="text" name="group_id" value="<?= $editStudent['group_id'] ?? '' ?>" required>
            </div>
            
            <button type="submit" name="<?= $editStudent ? 'update' : 'add' ?>">
                <?= $editStudent ? 'Update Student' : 'Add Student' ?>
            </button>
            
            <?php if ($editStudent): ?>
                <a href="manage_students.php"><button type="button">Cancel</button></a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Students List -->
    <div class="container">
        <h2>All Students (<?= count($students) ?>)</h2>
        <?php if (empty($students)): ?>
            <p>No students found. Add the first student above.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Matricule</th>
                        <th>Group</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $s): ?>
                    <tr>
                        <td><?= $s['id'] ?></td>
                        <td><?= htmlspecialchars($s['fullname']) ?></td>
                        <td><?= htmlspecialchars($s['matricule']) ?></td>
                        <td><?= htmlspecialchars($s['group_id']) ?></td>
                        <td>
                            <a href="?action=edit&id=<?= $s['id'] ?>" class="btn-edit">Edit</a>
                            <a href="?action=delete&id=<?= $s['id'] ?>" class="btn-delete" 
                               onclick="return confirm('Delete this student?')">Delete</a>
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
