/*** -------------------- INVENTORY EXPORT MODAL -------------------- ***/
const inventoryDownloadModal = document.querySelector(".confirmation-modal#downloadModal");
const inventoryModalTitle = inventoryDownloadModal.querySelector("#downloadModalTitle");
const inventoryModalMessage = inventoryDownloadModal.querySelector("#downloadModalMessage");
const inventoryConfirmBtn = inventoryDownloadModal.querySelector("#confirmDownloadBtn");
const inventoryCancelBtn = inventoryDownloadModal.querySelector("#cancelDownloadBtn");
const inventoryCloseBtn = inventoryDownloadModal.querySelector("#closeDownloadModal");

function closeInventoryDownloadModal() {
  inventoryDownloadModal.style.display = "none";
}

// Close modal events
inventoryCancelBtn.onclick = closeInventoryDownloadModal;
inventoryCloseBtn.onclick = closeInventoryDownloadModal;
window.onclick = function (event) {
  if (event.target === inventoryDownloadModal) closeInventoryDownloadModal();
};

// Trigger modal when inventory export is clicked
document.querySelectorAll(".export-dropdown .dropdown-menu li[data-export='excel']").forEach((item) => {
  item.addEventListener("click", function () {
    inventoryModalTitle.textContent = "Download Excel";
    inventoryModalMessage.textContent = "Do you want to download the Excel file for all inventory items?";
    inventoryDownloadModal.style.display = "flex";

    inventoryConfirmBtn.onclick = function () {
      // Redirect to your PHP export script
      window.location.href = "../php/export-inventory-excel.php";
      closeInventoryDownloadModal();
    };
  });
});
