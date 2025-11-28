$(document).ready(function () {

  function recalcAll() {
    $("#att-table tbody tr").each(function () {
      let abs = 0;
      let par = 0;

      // count absences (unchecked checkboxes = absent)
      $(this).find("td.s .attendance-check").each(function () {
        if (!$(this).is(':checked')) {
          abs++;
        }
      });

      // count participation
      $(this).find("td.p").each(function () {
        let v = parseInt($(this).text());
        if (v > 0) par++;
      });

      $(this).find(".abs-count").text(abs);
      $(this).find(".par-count").text(par);

      $(this).removeClass("good warn bad");

      if (abs < 3) $(this).addClass("good");
      else if (abs <= 4) $(this).addClass("warn");
      else $(this).addClass("bad");

      let msg =
        abs < 3 && par >= 3
          ? "Good attendance - Excellent participation"
          : abs <= 4
          ? "Warning - attendance low - You need to participate more"
          : "Excluded - too many absences - You need to participate more";

      $(this).find(".msg").text(msg);
    });
  }

  recalcAll(); // IMPORTANT

  // Watch for checkbox changes and recalculate
  $(document).on('change', '.attendance-check', function() {
    recalcAll();
  });


  // SEARCH (Tutorial 2)
  $("#search-name").on("input", function () {
    let term = $(this).val().toLowerCase();

    $("#att-table tbody tr").each(function () {
      let last = $(this).children().eq(1).text().toLowerCase();
      let first = $(this).children().eq(2).text().toLowerCase();

      if (last.includes(term) || first.includes(term)) $(this).show();
      else $(this).hide();
    });
  });


  // SORT by absences
  $("#sort-abs").click(function () {
    let rows = $("#att-table tbody tr").get();

    rows.sort(function (a, b) {
      let A = parseInt($(a).find(".abs-count").text());
      let B = parseInt($(b).find(".abs-count").text());
      return A - B;
    });

    $.each(rows, function (i, r) {
      $("#att-table tbody").append(r);
    });

    $("#sort-status").text("Currently sorted by absences (ascending)");
  });

  // SORT by participation desc
  $("#sort-par").click(function () {
    let rows = $("#att-table tbody tr").get();

    rows.sort(function (a, b) {
      let A = parseInt($(a).find(".par-count").text());
      let B = parseInt($(b).find(".par-count").text());
      return B - A;
    });

    $.each(rows, function (i, r) {
      $("#att-table tbody").append(r);
    });

    $("#sort-status").text("Currently sorted by participation (descending)");
  });


  // TUTORIAL 2 EXERCISE 2 - FORM VALIDATION
  $("#addForm").submit(function (e) {
    e.preventDefault();

    // Clear previous errors
    $(".error").text("");

    let valid = true;

    // Validate Student ID (numbers only, not empty)
    let studentId = $("#student_id").val().trim();
    if (studentId === "") {
      $("#err-id").text("Student ID is required");
      valid = false;
    } else if (!/^\d+$/.test(studentId)) {
      $("#err-id").text("Student ID must contain numbers only");
      valid = false;
    }

    // Validate Last Name (letters only)
    let lastName = $("#last_name").val().trim();
    if (lastName === "") {
      $("#err-last").text("Last name is required");
      valid = false;
    } else if (!/^[a-zA-Z\s]+$/.test(lastName)) {
      $("#err-last").text("Last name must contain letters only");
      valid = false;
    }

    // Validate First Name (letters only)
    let firstName = $("#first_name").val().trim();
    if (firstName === "") {
      $("#err-first").text("First name is required");
      valid = false;
    } else if (!/^[a-zA-Z\s]+$/.test(firstName)) {
      $("#err-first").text("First name must contain letters only");
      valid = false;
    }

    // Validate Email (proper format)
    let email = $("#email").val().trim();
    if (email === "") {
      $("#err-email").text("Email is required");
      valid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      $("#err-email").text("Email must be in valid format (example@example.com)");
      valid = false;
    }

    if (!valid) {
      return false; // Prevent form submission
    }

    // TUTORIAL 2 EXERCISE 3 - ADD STUDENT DYNAMICALLY
    $.post("add_student.php", $("#addForm").serialize(), function (res) {
      $("#add-confirm").text(res.message).css("color", "green");

      // Add new row to table dynamically
      let newRow = `
        <tr>
          <td>${studentId}</td>
          <td>${lastName}</td>
          <td>${firstName}</td>
          <td class="s"><input type="checkbox" class="attendance-check" checked></td><td class="p">0</td>
          <td class="s"><input type="checkbox" class="attendance-check" checked></td><td class="p">0</td>
          <td class="s"><input type="checkbox" class="attendance-check" checked></td><td class="p">0</td>
          <td class="s"><input type="checkbox" class="attendance-check" checked></td><td class="p">0</td>
          <td class="s"><input type="checkbox" class="attendance-check" checked></td><td class="p">0</td>
          <td class="s"><input type="checkbox" class="attendance-check" checked></td><td class="p">0</td>
          <td class="abs-count"></td>
          <td class="par-count"></td>
          <td class="msg"></td>
        </tr>
      `;

      $("#student-rows").append(newRow);
      recalcAll();

      // Clear form
      $("#addForm")[0].reset();

      // Hide confirmation after 3 seconds
      setTimeout(function() {
        $("#add-confirm").text("");
      }, 3000);

    }, "json").fail(function(xhr) {
      // Handle error response from server
      let errorMsg = "Error adding student";
      
      if (xhr.responseJSON && xhr.responseJSON.errors) {
        errorMsg = xhr.responseJSON.errors.join(", ");
      }
      
      $("#add-confirm").text(errorMsg).css("color", "red");
      
      // Hide error after 5 seconds
      setTimeout(function() {
        $("#add-confirm").text("");
      }, 5000);
    });
  });


  // TUTORIAL 2 EXERCISE 4 - SHOW REPORT
  $("#show-report").click(function () {
    let totalStudents = $("#att-table tbody tr").length;
    let totalPresent = 0;
    let totalParticipated = 0;

    $("#att-table tbody tr").each(function () {
      let abs = parseInt($(this).find(".abs-count").text());
      let par = parseInt($(this).find(".par-count").text());

      // Count as "present" if less than 3 absences
      if (abs < 3) totalPresent++;

      // Count as "participated" if at least 3 participations
      if (par >= 3) totalParticipated++;
    });

    let reportHtml = `
      <h3>Attendance Report</h3>
      <p><strong>Total Students:</strong> ${totalStudents}</p>
      <p><strong>Students with Good Attendance (&lt;3 absences):</strong> ${totalPresent}</p>
      <p><strong>Students with Good Participation (≥3):</strong> ${totalParticipated}</p>
    `;

    $("#report-container").html(reportHtml).show();
  });


  // TUTORIAL 2 EXERCISE 5 - ROW HOVER AND CLICK
  $("#att-table tbody").on("mouseenter", "tr", function () {
    $(this).css("background-color", "#e0e0e0");
  });

  $("#att-table tbody").on("mouseleave", "tr", function () {
    // Restore original color class
    let abs = parseInt($(this).find(".abs-count").text());
    $(this).css("background-color", "");
  });

  $("#att-table tbody").on("click", "tr", function () {
    let id = $(this).children().eq(0).text();
    let lastName = $(this).children().eq(1).text();
    let firstName = $(this).children().eq(2).text();
    let abs = $(this).find(".abs-count").text();

    alert(`Student: ${firstName} ${lastName}\nNumber of Absences: ${abs}`);
  });


  // TUTORIAL 2 EXERCISE 6 - HIGHLIGHT EXCELLENT STUDENTS
  $("#highlight-excellent").click(function () {
    $("#att-table tbody tr").each(function () {
      let abs = parseInt($(this).find(".abs-count").text());

      if (abs < 3) {
        $(this).fadeOut(300).fadeIn(300).css({
          "background-color": "#90EE90",
          "font-weight": "bold"
        });
      }
    });
  });

  $("#reset-colors").click(function () {
    $("#att-table tbody tr").css({
      "background-color": "",
      "font-weight": "normal"
    });
    recalcAll(); // Reapply original color classes
  });

});
