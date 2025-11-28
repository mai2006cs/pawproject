<?php
// ============================================
// DATABASE SETUP & CONFIGURATION
// All-in-one file for database management
// ============================================

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'attendance_db');

// Get Database Connection
function getDB() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Run this file once to create database and tables
if (basename($_SERVER['PHP_SELF']) == 'setup.php') {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Setup</title>
    <style>
        body { font-family: Arial; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: green; background: #d4edda; padding: 10px; margin: 10px 0; border-radius: 5px; }
        .error { color: red; background: #f8d7da; padding: 10px; margin: 10px 0; border-radius: 5px; }
        button { padding: 12px 24px; background: #007bff; color: white; border: none; cursor: pointer; font-size: 16px; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>📊 Attendance System - Database Setup</h1>
    
    <?php
    if (isset($_POST['setup'])) {
        try {
            // Connect without database
            $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Create database
            $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
            echo "<div class='success'>✓ Database created</div>";
            
            $pdo->exec("USE " . DB_NAME);
            
            // Create students table
            $pdo->exec("CREATE TABLE IF NOT EXISTS students (
                id INT AUTO_INCREMENT PRIMARY KEY,
                fullname VARCHAR(255) NOT NULL,
                matricule VARCHAR(50) NOT NULL UNIQUE,
                group_id VARCHAR(50) NOT NULL
            )");
            echo "<div class='success'>✓ Students table created</div>";
            
            // Create sessions table
            $pdo->exec("CREATE TABLE IF NOT EXISTS attendance_sessions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                course_id VARCHAR(50) NOT NULL,
                group_id VARCHAR(50) NOT NULL,
                session_date DATE NOT NULL,
                opened_by VARCHAR(100) NOT NULL,
                status ENUM('open', 'closed') DEFAULT 'open'
            )");
            echo "<div class='success'>✓ Sessions table created</div>";
            
            echo "<div class='success'><strong>✓ Setup Complete!</strong></div>";
            echo "<p><a href='index.php'>Go to Attendance System</a> | <a href='manage_students.php'>Manage Students</a></p>";
            
        } catch (PDOException $e) {
            echo "<div class='error'>Error: " . $e->getMessage() . "</div>";
        }
    } else {
    ?>
        <p>Click the button below to create the database and tables.</p>
        <form method="POST">
            <button type="submit" name="setup">Create Database & Tables</button>
        </form>
    <?php } ?>
</body>
</html>
<?php
}
?>
