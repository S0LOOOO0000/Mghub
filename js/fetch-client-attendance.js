// =========================
// Load Attendance Table clientside
// =========================

function loadAttendanceTable() {
  fetch("../php/get-attendance-client.php")
    .then(res => res.text())
    .then(html => {
      document.getElementById("attendance-body").innerHTML = html;
    })
    .catch(err => console.error("Error loading attendance:", err));
}