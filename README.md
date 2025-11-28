## Project Files

**Main files you'll use:**
- `index.php` - Main attendance page with student list and interactive table
- `take_attendance.php` - Daily attendance marking page
- `manage_students.php` - Student database management
- `sessions.php` - Attendance session tracking
- `setup.php` - Database setup (run this first)

**Behind the scenes:**
- `add_student.php` - Processes student form submissions
- `style.css` - All the styling
- `app.js` - JavaScript functionality
- `data/` - Stores JSON files for students and daily attendance
## How It Works

### Main Page (index.php)

This is where you'll spend most of your time. The page shows an attendance table with checkboxes - just click them to mark students present or absent. The absence count updates automatically, and rows change color based on how many times someone's been absent:
- Green means they're doing fine (less than 3 absences)
- Yellow is a warning (3-4 absences)
- Red means they're at risk (5 or more absences)

You can also add new students using the form at the bottom, search for specific students, sort the table, and view statistics by clicking "Show Report".

### Daily Attendance (take_attendance.php)

Use this page to take attendance for the day. It shows all your students with radio buttons - select present or absent for each one, then save. The system won't let you take attendance twice for the same day (it saves to a JSON file dated with today's date).

### Student Management (manage_students.php)

This is your student database. Add new students with their full name, matricule number, and group. You can edit or delete existing students too. Everything here gets saved to MySQL, so it's permanent storage unlike the JSON files.

### Session Tracking (sessions.php)

Create attendance sessions by entering the course name, group, date, and professor. You can view all past sessions and close active ones. This is useful for keeping track of which classes have been held.

### Database Setup (setup.php)

Run this file once when you first install the system. It creates the database and tables you need. Don't run it again unless you want to reset everything.

---

## Tutorial Exercises Implemented

This project covers all three tutorials from the Advanced Web Programming course

## 🔗 All Page URLs

| Page | URL | Purpose |
|------|-----|---------|
| **Setup** | `http://localhost/attendance_app/setup.php` | Create database (run once) |
| **Home** | `http://localhost/attendance_app/index.php` | Main attendance interface |
| **Take Attendance** | `http://localhost/attendance_app/take_attendance.php` | Mark daily attendance |
| **Manage Students** | `http://localhost/attendance_app/manage_students.php` | Database CRUD |
| **Sessions** | `http://localhost/attendance_app/sessions.php` | Session management |

---
## Key Features

**Interactive Checkboxes**  
On the main page, checked boxes mean present and unchecked means absent. The counts update automatically as you click.

**Color-Coded Rows**  
Rows turn green (good), yellow (warning), or red (at-risk) based on absence count. Makes it easy to spot students who need attention.

**Search and Sort**  
Type in the search box to filter students, or use the sort buttons to organize by absences or participation scores.

**Data Storage**  
Student information gets saved in both JSON files (in the `data/` folder) and MySQL database. Daily attendance is stored as `attendance_YYYY-MM-DD.json` files.

---

## Troubleshooting

**"Page not found" error**  
Check that WAMP is running (icon should be green) and your URL starts with `http://localhost/`

**"Connection failed" error**  
You probably need to run setup.php first. Also double-check that MySQL is running in WAMP.

**No students showing up**  
Add some students first using the form on index.php or through manage_students.php. Also make sure the `data/students.json` file exists.

**"Student ID already exists" error**  
You're trying to add a student with an ID that's already in the system. Each student must have a unique ID number. Check the attendance table to see existing students.

**Checkboxes not updating counts**  
Press F12 and check the browser console for errors. Make sure you have internet connection (jQuery loads from a CDN). Try clearing your cache with Ctrl+Shift+R.

**Can't take attendance twice in one day**  
That's intentional - the system prevents duplicate attendance entries. If you need to test it again, delete today's file from the `data/` folder.

---

## Technologies

- HTML5, CSS3, JavaScript (ES6+)
- jQuery 3.7.0
- PHP 7.4+
- MySQL 5.7+ (via WAMP)
- Apache server

The code uses prepared statements for security, AJAX for dynamic updates, and both JSON files and MySQL for data storage.

---

## Quick Reference

- **View attendance** → index.php
- **Add students** → index.php (form at bottom)
- **Take daily attendance** → take_attendance.php
- **Manage student database** → manage_students.php
- **Track sessions** → sessions.php
- **Reset database** → setup.php