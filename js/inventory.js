// =============================
// inventory.js
// MG Hub Inventory Module
// Handles: Form Validation, Modals, AJAX Actions, Popups
// =============================

document.addEventListener("DOMContentLoaded", () => {
  initModals();
  initActionHandlers();
  initInventoryForms(); // Add/Edit via AJAX
  handlePopupsFromURL();
});

/* -----------------------------------------
   MODALS (Add, Edit, Delete)
----------------------------------------- */
function initModals() {
  const addModal = document.getElementById("addInventoryModal");
  const openAddBtn = document.getElementById("openInventoryModal");
  const deleteModal = document.getElementById("deleteInventoryModal");

  if (addModal && openAddBtn) {
    openAddBtn.addEventListener("click", () => addModal.classList.add("show"));
  }

  // Close buttons
document.querySelectorAll(".modal-inv .close-btn").forEach(btn => {
  btn.addEventListener("click", () => btn.closest(".modal-inv").classList.remove("show"));
});

  // Cancel Delete
  const cancelDeleteBtn = document.getElementById("cancelDeleteInventoryBtn");
  if (cancelDeleteBtn) cancelDeleteBtn.addEventListener("click", () => deleteModal.classList.remove("show"));
}

/* -----------------------------------------
   FORM VALIDATION & AJAX (Add/Edit)
----------------------------------------- */
function initInventoryForms() {
  const addForm = document.getElementById("addInventoryForm");
  const editForm = document.getElementById("editInventoryForm");
  const deleteForm = document.getElementById("deleteInventoryForm");

  if (addForm) {
    addForm.addEventListener("submit", async e => {
      e.preventDefault();
      if (!validateInventoryForm(addForm)) return;
      const formData = new FormData(addForm);
      const res = await postForm("../php/add-inventory.php", formData);
      handleResponse(res, addForm, "add");
    });
  }

  if (editForm) {
    editForm.addEventListener("submit", async e => {
      e.preventDefault();
      if (!validateInventoryForm(editForm)) return;
      const formData = new FormData(editForm);
      const res = await postForm("../php/edit-inventory.php", formData);
      handleResponse(res, editForm, "edit");
    });
  }

  if (deleteForm) {
    const confirmBtn = document.getElementById("confirmDelete");
    confirmBtn?.addEventListener("click", async () => {
      const formData = new FormData(deleteForm);
      const res = await postForm("../php/delete-inventory.php", formData);
      handleResponse(res, deleteForm, "delete");
    });
  }
}

function validateInventoryForm(form) {
  let isValid = true;
  const name = form.querySelector("[name='item_name']");
  const quantity = form.querySelector("[name='item_quantity']");
  const category = form.querySelector("[name='item_category']");

  clearFormErrors(form);

  if (!name.value.trim()) { showFormError(name, "Item name is required."); isValid = false; }
  if (!quantity.value.trim() || isNaN(quantity.value) || quantity.value < 0) { showFormError(quantity, "Valid quantity is required."); isValid = false; }
  if (!category.value.trim()) { showFormError(category, "Category is required."); isValid = false; }

  return isValid;
}

function showFormError(input, message) {
  const error = document.createElement("span");
  error.className = "error-message";
  error.style.color = "red";
  error.textContent = message;
  input.parentNode.appendChild(error);
}

function clearFormErrors(form) {
  form.querySelectorAll(".error-message").forEach(el => el.remove());
}

/* -----------------------------------------
   EDIT & DELETE BUTTONS
----------------------------------------- */
function initActionHandlers() {
  document.addEventListener("click", e => {
    // Edit
    if (e.target.closest(".edit-inv-btn")) {
      const btn = e.target.closest(".edit-inv-btn");
      const form = document.getElementById("editInventoryForm");
      form.querySelector("#edit_item_name").value = btn.dataset.name;
      form.querySelector("#edit_item_quantity").value = btn.dataset.quantity;
      form.querySelector("#edit_item_category").value = btn.dataset.category;
      form.querySelector("#edit_inventory_id").value = btn.dataset.inventoryId;
      document.getElementById("editInventoryModal").classList.add("show");
    }

    // Delete
    if (e.target.closest(".delete-inv-btn")) {
      const btn = e.target.closest(".delete-inv-btn");
      const deleteModal = document.getElementById("deleteInventoryModal");
      document.getElementById("delete_inventory_id").value = btn.dataset.inventoryId;
      document.getElementById("delete_item_name").innerHTML = `Are you sure you want to delete <strong>${btn.dataset.inventoryName}</strong>?`;
      deleteModal.classList.add("show");
    }
  });
}

/* -----------------------------------------
   AJAX POST HELPER
----------------------------------------- */
async function postForm(url, formData) {
  try {
    const response = await fetch(url, { method: "POST", body: formData });
    return await response.json();
  } catch (err) {
    return { status: "error", message: "Server error" };
  }
}

/* -----------------------------------------
   RESPONSE HANDLER
----------------------------------------- */
function handleResponse(res, form, type) {
  if (res.status === "success") {
    showPopup(res.message, "success");
    form.closest(".modal").classList.remove("show");
    setTimeout(() => window.location.reload(), 1000); // refresh to show changes
  } else {
    showPopup(res.message, "error");
    if (type === "add") document.getElementById("addInventoryModal").classList.add("show");
    if (type === "edit") document.getElementById("editInventoryModal").classList.add("show");
  }
}

/* -----------------------------------------
   POPUPS (Success/Error)
----------------------------------------- */
function showPopup(message, type = "success") {
  if (!message) return;
  const popup = document.createElement("div");
  popup.className = type === "success" ? "success-popup" : "error-popup";
  popup.textContent = message;
  document.body.appendChild(popup);
  popup.style.opacity = "1";
  popup.style.transition = "opacity 0.5s";
  setTimeout(() => { popup.style.opacity = "0"; setTimeout(() => popup.remove(), 500); }, 3000);
}

function handlePopupsFromURL() {
  const urlParams = new URLSearchParams(window.location.search);
  const successMessage = urlParams.get("success");
  const errorMessage = urlParams.get("error");
  if (successMessage) showPopup(successMessage, "success");
  if (errorMessage) {
    showPopup(errorMessage, "error");
    const addModal = document.getElementById("addInventoryModal");
    if (addModal) addModal.classList.add("show");
  }
}
