let html5QrCode;
// =========================
// Open QR Scanner
// =========================

function openQrScanner(action) {
  const modal = document.getElementById("qrModal");
  const title = document.getElementById("qrModalTitle");
  title.textContent = action === "change" ? "Request Change - Scan QR"
                    : "Request Leave - Scan QR";
  modal.style.display = "flex";

  // ✅ Create scanner instance
  html5QrCode = new Html5Qrcode("qr-reader");

  // ✅ Use static getCameras
  Html5Qrcode.getCameras()
    .then(cameras => {
      if (cameras.length > 0) {
        html5QrCode.start(
          cameras[0].id, // first camera
          { fps: 10, qrbox: 250 },
          qrMessage => {
            fetch("../php/attendance-function.php", {
              method: "POST",
              headers: { "Content-Type": "application/x-www-form-urlencoded" },
              body: "qr_code=" + encodeURIComponent(qrMessage) + "&action=" + action
            })
              .then(res => res.json())
              .then(data => {
                closeQrScanner();
                showAttendanceResult(data);
              })
              .catch(err => {
                closeQrScanner();
                console.error("Fetch error:", err);
              });
          },
          errorMessage => {
            console.warn("QR Scan error:", errorMessage);
          }
        );
      }
    })
    .catch(err => console.error("Camera error:", err));
}

// =========================
// Close QR Scanner
// =========================
function closeQrScanner() {
  const modal = document.getElementById("qrModal");
  modal.style.display = "none";

  if (html5QrCode) {
    html5QrCode.stop()
      .then(() => html5QrCode.clear())
      .catch(err => console.error("Stop error:", err));
  }
}

document.getElementById("closeQrModal")?.addEventListener("click", closeQrScanner);

// =========================
// Show Attendance Result Modal
// =========================
function showAttendanceResult(data) {
  const modal = document.getElementById("attendanceModal");
  const message = document.getElementById("attendanceMessage");
  const details = document.getElementById("attendanceDetails");

  modal.classList.remove("success", "error");
  modal.classList.add(data.status);

  message.textContent = data.message;
  details.innerHTML = `
    Name: ${data.name || '-'}<br>
    Time: ${data.time || '--'}<br>
    Date: ${data.date || '--'}
  `;

  modal.style.display = "flex";
  setTimeout(() => { modal.style.display = "none"; }, 4000);
}

// =========================
// Helpers for Modals
// =========================
function convertTo24Hour(timeStr) {
  if (!timeStr || timeStr === '--') return '';
  const [time, modifier] = timeStr.split(' ');
  let [hours, minutes] = time.split(':').map(Number);
  if (modifier.toUpperCase() === 'PM' && hours !== 12) hours += 12;
  if (modifier.toUpperCase() === 'AM' && hours === 12) hours = 0;
  return `${hours.toString().padStart(2,'0')}:${minutes.toString().padStart(2,'0')}`;
}

function convertToInputDate(dateStr) {
  if (!dateStr || dateStr === '--') return '';
  const date = new Date(dateStr);
  const y = date.getFullYear();
  const m = (date.getMonth()+1).toString().padStart(2,'0');
  const d = date.getDate().toString().padStart(2,'0');
  return `${y}-${m}-${d}`;
}

// =========================
// Handle Attendance Action Modals
// =========================
function handleAttendanceActions(e) {
  const target = e.target;
  const menuItem = target.closest(".edit-btn, .request-btn, .leave-btn");
  if (!menuItem) return;

  const tr = menuItem.closest("tr") || document.querySelector(`tr[data-employee-id='${menuItem.dataset.employeeId}']`);
  if (!tr) return;

  const employeeId = menuItem.dataset.employeeId || tr.dataset.employeeId;

  // Edit Attendance
  if (menuItem.classList.contains("edit-btn")) {
    const editModal = document.getElementById("editAttendanceModal");
    editModal.style.display = "flex";

    const timeIn = tr.querySelector("td:nth-child(7)").textContent.trim();
    const timeOut = tr.querySelector("td:nth-child(8)").textContent.trim();
    const date = tr.querySelector("td:nth-child(9)").textContent.trim();
    const status = tr.querySelector("td:nth-child(6) span").textContent.trim();

    document.getElementById("edit_attendance_id").value = employeeId;
    document.getElementById("edit_time_in").value = timeIn === '--' ? '' : convertTo24Hour(timeIn);
    document.getElementById("edit_time_out").value = timeOut === '--' ? '' : convertTo24Hour(timeOut);
    document.getElementById("edit_attendance_date").value = convertToInputDate(date);
    document.getElementById("edit_attendance_status").value = status;
  }

  // Change Shift
  if (menuItem.classList.contains("request-btn")) {
    const shiftModal = document.getElementById("changeShiftModal");
    shiftModal.style.display = "flex";
    document.getElementById("change_request_employee_id").value = employeeId;
  }

  // Leave Request
  if (menuItem.classList.contains("leave-btn")) {
    const leaveModal = document.getElementById("leaveRequestModal");
    leaveModal.style.display = "flex";
    document.getElementById("leave_request_employee_id").value = employeeId;
  }

  // Close floating dropdown after click
  document.querySelectorAll('.floating-dropdown').forEach(fd => fd.remove());
  const container = target.closest('.icon-circle, .attendance-icon');
  if (container) container.dataset.open = "false";
}

// =========================
// DOMContentLoaded
// =========================
document.addEventListener("DOMContentLoaded", () => {

  // Close buttons for modals
  document.getElementById("closeEditAttendance")?.addEventListener("click", () => {
    document.getElementById("editAttendanceModal").style.display = "none";
  });
  document.querySelector("#changeShiftModal .close-btn")?.addEventListener("click", () => {
    document.getElementById("changeShiftModal").style.display = "none";
  });
  document.querySelector("#leaveRequestModal .close-btn")?.addEventListener("click", () => {
    document.getElementById("leaveRequestModal").style.display = "none";
  });

  // Delegated click for attendance actions
  document.addEventListener("click", handleAttendanceActions);
});

// =========================
// Auto Refresh Every 30 Seconds
// =========================
setInterval(() => {
  updatePresentToday();
}, 30000);
