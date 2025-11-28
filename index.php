<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Attendance System</title>

  <link rel="stylesheet" href="style.css">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>
<body>

  <!-- NAVBAR -->
  <nav class="navbar">
    <a href="index.php">Home</a>
    <a href="index.php">Attendance List</a>
    <a href="take_attendance.php">Take Attendance</a>
    <a href="manage_students.php">Manage Students</a>
    <a href="sessions.php">Sessions</a>
  </nav>

  <main>

    <!-- ATTENDANCE LIST -->
    <section id="attendance">
      <h2>Attendance List</h2>

      <div class="controls">
        <input id="search-name" placeholder="Search by Name">
        <button id="sort-abs">Sort by Absences (Asc)</button>
        <button id="sort-par">Sort by Participation (Desc)</button>
        <button id="show-report">Show Report</button>
        <button id="highlight-excellent">Highlight Excellent Students</button>
        <button id="reset-colors">Reset Colors</button>
      </div>
      <div id="sort-status" style="margin-top: 10px; color: #666; font-style: italic;"></div>
      <div id="report-container" style="margin-top: 20px; padding: 15px; background: #f0f8ff; border: 1px solid #0066cc; display: none;"></div>

      <table id="att-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Last</th>
            <th>First</th>

            <th>S1</th><th>P1</th>
            <th>S2</th><th>P2</th>
            <th>S3</th><th>P3</th>
            <th>S4</th><th>P4</th>
            <th>S5</th><th>P5</th>
            <th>S6</th><th>P6</th>

            <th>Total Abs</th>
            <th>Total Par</th>
            <th>Message</th>
          </tr>
        </thead>
        <tbody id="student-rows">

          <!-- SAMPLE (2 EXAMPLES) -->
          <tr>
            <td>101</td><td>Ahmed</td><td>Sara</td>

            <td class="s"><input type="checkbox" class="attendance-check" checked></td><td class="p">1</td>
            <td class="s"><input type="checkbox" class="attendance-check"></td><td class="p">0</td>
            <td class="s"><input type="checkbox" class="attendance-check" checked></td><td class="p">1</td>
            <td class="s"><input type="checkbox" class="attendance-check" checked></td><td class="p">1</td>
            <td class="s"><input type="checkbox" class="attendance-check"></td><td class="p">0</td>
            <td class="s"><input type="checkbox" class="attendance-check" checked></td><td class="p">1</td>

            <td class="abs-count"></td>
            <td class="par-count"></td>
            <td class="msg"></td>
          </tr>

          <tr>
            <td>102</td><td>Khan</td><td>Layla</td>

            <td class="s"><input type="checkbox" class="attendance-check"></td><td class="p">0</td>
            <td class="s"><input type="checkbox" class="attendance-check" checked></td><td class="p">1</td>
            <td class="s"><input type="checkbox" class="attendance-check" checked></td><td class="p">1</td>
            <td class="s"><input type="checkbox" class="attendance-check"></td><td class="p">0</td>
            <td class="s"><input type="checkbox" class="attendance-check" checked></td><td class="p">1</td>
            <td class="s"><input type="checkbox" class="attendance-check" checked></td><td class="p">1</td>

            <td class="abs-count"></td>
            <td class="par-count"></td>
            <td class="msg"></td>
          </tr>

          <tr>
            <td>103</td><td>Beanli</td><td>Amina</td>

            <td class="s"><input type="checkbox" class="attendance-check"></td><td class="p">0</td>
            <td class="s"><input type="checkbox" class="attendance-check"></td><td class="p">0</td>
            <td class="s"><input type="checkbox" class="attendance-check"></td><td class="p">0</td>
            <td class="s"><input type="checkbox" class="attendance-check"></td><td class="p">0</td>
            <td class="s"><input type="checkbox" class="attendance-check"></td><td class="p">0</td>
            <td class="s"><input type="checkbox" class="attendance-check" checked></td><td class="p">0</td>

            <td class="abs-count"></td>
            <td class="par-count"></td>
            <td class="msg"></td>
          </tr>

        </tbody>
      </table>
    </section>

    <!-- ADD STUDENT FORM -->
    <section id="add-student">
      <h2>Add Student</h2>

      <form id="addForm">
        <label>Student ID <input id="student_id" name="student_id" type="text" pattern="[0-9]+" title="Only numbers allowed" required></label>
        <div class="error" id="err-id"></div>

        <label>Last Name <input id="last_name" name="last_name" type="text" pattern="[a-zA-Z\s]+" title="Only letters allowed" required></label>
        <div class="error" id="err-last"></div>

        <label>First Name <input id="first_name" name="first_name" type="text" pattern="[a-zA-Z\s]+" title="Only letters allowed" required></label>
        <div class="error" id="err-first"></div>

        <label>Email <input id="email" name="email" type="email" required></label>
        <div class="error" id="err-email"></div>

        <button type="submit">Submit</button>
      </form>

      <div id="add-confirm"></div>
    </section>

  </main>

<script src="app.js"></script>

</body>
</html>
