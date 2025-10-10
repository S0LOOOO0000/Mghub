document.addEventListener("DOMContentLoaded", () => {
  /*** -------------------- ADD EMPLOYEE MODAL -------------------- ***/
  const addModal = document.getElementById("employeeModal");
  const addCloseBtn = document.getElementById("closeModal");
  const addBtn = document.querySelector(".btn-add"); 

  if (addBtn) addBtn.addEventListener("click", () => (addModal.style.display = "flex"));
  if (addCloseBtn) addCloseBtn.addEventListener("click", () => (addModal.style.display = "none"));
  window.addEventListener("click", (e) => {
    if (e.target === addModal) addModal.style.display = "none";
  });

  /*** -------------------- STATION → ROLE POPULATION -------------------- ***/
  const roleOptions = {
    Cafe: ["Team Leader", "Barista", "Cashier / Waitress", "On-Call", "Head Chef", "Line Cook", "Kitchen Helper"],
    Spa: ["Team Leader / Receptionist", "Massage Therapist", "Nail Technician", "Brow Technician"],
    "Beauty Lounge": ["Team Leader / Receptionist", "Massage Therapist", "Nail Technician", "Brow Technician"],
  };

  function populateRolesByStation(station, selectElem, selectedRole = "") {
    const roles = roleOptions[station] || [];
    selectElem.innerHTML = '<option value="">-- Select Role --</option>';
    roles.forEach((role) => {
      const option = document.createElement("option");
      option.value = role;
      option.textContent = role;
      if (role === selectedRole) option.selected = true;
      selectElem.appendChild(option);
    });
  }

  // Add Employee modal role population
  const addRoleSelect = document.getElementById("role");
  const addStationSelect = document.getElementById("work_station");
  if (addStationSelect && addRoleSelect) {
    addStationSelect.addEventListener("change", function () {
      populateRolesByStation(this.value, addRoleSelect);
    });
  }

  /*** -------------------- INPUT VALIDATION -------------------- ***/
  document.getElementById("first_name").addEventListener("input", function () {
    this.value = this.value.replace(/[^A-Za-z\s'-]/g, "");
  });
  document.getElementById("last_name").addEventListener("input", function () {
    this.value = this.value.replace(/[^A-Za-z\s'-]/g, "");
  });
  document.getElementById("contact_number").addEventListener("input", function () {
    this.value = this.value.replace(/[^0-9]/g, "");
  });

  /*** -------------------- SUCCESS / ERROR POPUP -------------------- ***/
  function showPopup(message, type = "success") {
    if (!message) return;
    const popup = document.createElement("div");
    popup.className = type === "success" ? "success-popup" : "error-popup";
    popup.textContent = message;
    document.body.appendChild(popup);
    popup.style.opacity = "1";
    popup.style.transition = "opacity 0.5s";
    setTimeout(() => {
      popup.style.opacity = "0";
      setTimeout(() => popup.remove(), 500);
    }, 3000);
  }

  const urlParams = new URLSearchParams(window.location.search);
  const successMessage = urlParams.get("success");
  const errorMessage = urlParams.get("error");
  if (successMessage) showPopup(successMessage, "success");
  if (errorMessage) {
    showPopup(errorMessage, "error");
    addModal.style.display = "flex";
  }

  /*** -------------------- EDIT EMPLOYEE MODAL -------------------- ***/
  const editModal = document.getElementById("editEmployeeModal");
  const editCloseBtn = document.getElementById("closeEditModal");

  document.addEventListener("click", (e) => {
    if (e.target.classList.contains("edit-btn")) {
      const btn = e.target;
      editModal.style.display = "flex";

      document.getElementById("edit_employee_id").value = btn.dataset.employeeId;
      document.getElementById("edit_employee_code_text").textContent = btn.dataset.employeeCode;
      document.getElementById("edit_first_name").value = btn.dataset.firstName;
      document.getElementById("edit_last_name").value = btn.dataset.lastName;
      document.getElementById("edit_email_address").value = btn.dataset.email;
      document.getElementById("edit_contact_number").value = btn.dataset.contact;
      document.getElementById("edit_work_station").value = btn.dataset.station;
      populateRolesByStation(btn.dataset.station, document.getElementById("edit_role"), btn.dataset.role);
      document.getElementById("edit_shift").value = btn.dataset.shift;
      document.getElementById("edit_employee_image_preview").src = btn.dataset.image;
      document.getElementById("edit_employee_qrcode").src = btn.dataset.qr;
    }
  });

  if (editCloseBtn) editCloseBtn.addEventListener("click", () => (editModal.style.display = "none"));
  window.addEventListener("click", (e) => {
    if (e.target === editModal) editModal.style.display = "none";
  });

  const editStationSelect = document.getElementById("edit_work_station");
  const editRoleSelect = document.getElementById("edit_role");
  if (editStationSelect && editRoleSelect) {
    editStationSelect.addEventListener("change", function () {
      populateRolesByStation(this.value, editRoleSelect);
    });
  }

  /*** -------------------- UPDATE TABLE AFTER EDIT -------------------- ***/
  const editForm = document.querySelector("#editEmployeeModal form");
  if (editForm) {
    editForm.addEventListener("submit", function () {
      const employeeId = document.getElementById("edit_employee_id").value;
      const row = document.querySelector(`.edit-btn[data-employee-id="${employeeId}"]`).closest("tr");
      const editBtn = row.querySelector(".edit-btn");

      const firstName = document.getElementById("edit_first_name").value;
      const lastName = document.getElementById("edit_last_name").value;
      const email = document.getElementById("edit_email_address").value;
      const contact = document.getElementById("edit_contact_number").value;
      const station = document.getElementById("edit_work_station").value;
      const role = document.getElementById("edit_role").value;
      const shift = document.getElementById("edit_shift").value;
      const image = document.getElementById("edit_employee_image_preview").src;
      const qr = document.getElementById("edit_employee_qrcode").src;

      // Update row
      row.querySelector(".emp-name span").textContent = firstName + " " + lastName;
      row.querySelector(".emp-name p").textContent = email;
      row.querySelector(".emp-contact").textContent = contact;
      row.querySelector(".emp-station span").textContent = station;
      row.querySelector(".emp-station p").textContent = role;
      const rowShift = row.querySelector(".shift");
      rowShift.textContent = shift;
      rowShift.className = "shift " + shift.toLowerCase();
      row.querySelector(".emp-image img").src = image;
      row.querySelector(".emp-qr img").src = qr;

      // Update button dataset
      Object.assign(editBtn.dataset, { firstName, lastName, email, contact, station, role, shift, image, qr });

      editModal.style.display = "none";
    });
  }

 const deleteModal = document.getElementById("deleteConfirmModal");
const closeDeleteModal = document.getElementById("closeDeleteModal");
const cancelDeleteBtn = document.getElementById("cancelDeleteBtn");
const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
const deleteMessage = document.getElementById("deleteMessage");

let employeeIdToDelete = null;
let rowToDelete = null;

// Open delete modal
document.addEventListener("click", (e) => {
  if (e.target.classList.contains("delete-btn")) {
    employeeIdToDelete = e.target.dataset.employeeId || null;
    const employeeName = e.target.dataset.employeeName || "this employee";
    rowToDelete = e.target.closest("tr");

    if (deleteMessage) {
      deleteMessage.textContent = `Are you sure you want to delete employee ${employeeName}?`;
    }
    deleteModal?.classList.add("show");
  }
});

// Close delete modal (X, Cancel, or outside click)
function closeDelete() {
  deleteModal?.classList.remove("show");
  employeeIdToDelete = null;
  rowToDelete = null;
}
closeDeleteModal?.addEventListener("click", closeDelete);
cancelDeleteBtn?.addEventListener("click", closeDelete);
window.addEventListener("click", (e) => {
  if (e.target === deleteModal) closeDelete();
});

// Confirm delete
confirmDeleteBtn?.addEventListener("click", async () => {
  if (!employeeIdToDelete) return;

  try {
    const res = await fetch("../php/delete-function.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "employee_id=" + encodeURIComponent(employeeIdToDelete),
    });

    const data = await res.text();

    if (data.trim() === "success") {
      rowToDelete?.remove();
      showPopup("Employee deleted successfully!", "success");
    } else {
      showPopup("Failed to delete: " + data, "error");
    }
  } catch (err) {
    console.error("Delete error:", err);
    showPopup("Error deleting employee.", "error");
  } finally {
    closeDelete();
  }
});


  /*** -------------------- PREVIEW IMAGE & QR -------------------- ***/
  const previewModal = document.getElementById("imagePreviewModal");
  const previewImg = document.getElementById("previewImage");
  const closePreviewBtn = previewModal.querySelector(".close-btn");
  document.querySelectorAll(".preview-img").forEach((img) => {
    img.addEventListener("click", () => {
      previewModal.classList.add("show");
      previewImg.src = img.src;
    });
  });
  if (closePreviewBtn) closePreviewBtn.addEventListener("click", () => previewModal.classList.remove("show"));
  window.addEventListener("click", (e) => {
    if (e.target === previewModal) previewModal.classList.remove("show");
  });


});
