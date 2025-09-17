  /*** -------------------- EXPORT / DOWNLOAD MODAL -------------------- ***/
  const downloadModal = document.querySelector(".confirmation-modal#downloadModal");
  const modalTitle = downloadModal.querySelector("#downloadModalTitle");
  const modalMessage = downloadModal.querySelector("#downloadModalMessage");
  const confirmBtn = downloadModal.querySelector("#confirmDownloadBtn");
  const cancelBtn = downloadModal.querySelector("#cancelDownloadBtn");
  const closeBtn = downloadModal.querySelector("#closeDownloadModal");

  function closeDownloadModal() {
    downloadModal.style.display = "none";
  }
  cancelBtn.onclick = closeDownloadModal;
  closeBtn.onclick = closeDownloadModal;
  window.onclick = function (event) {
    if (event.target === downloadModal) closeDownloadModal();
  };

  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("download-pdf")) {
      const empCode = e.target.dataset.employeeCode;
      const firstName = e.target.dataset.firstName;
      const lastName = e.target.dataset.lastName;
      modalTitle.textContent = "Download PDF";
      modalMessage.textContent = `Do you want to download the PDF file for ${firstName} ${lastName}?`;
      downloadModal.style.display = "flex";
      confirmBtn.onclick = function () {
        window.location.href = `../php/download-employee-pdf.php?employee_code=${empCode}`;
        closeDownloadModal();
      };
    }
    if (e.target.classList.contains("download-qr-btn")) {
      const qrFile = e.target.dataset.qr;
      const firstName = e.target.dataset.firstName;
      const lastName = e.target.dataset.lastName;
      modalTitle.textContent = "Download QR Code";
      modalMessage.textContent = `Do you want to download the QR Code for ${firstName} ${lastName}?`;
      downloadModal.style.display = "flex";
      confirmBtn.onclick = function () {
        const link = document.createElement("a");
        link.href = qrFile;
        link.download = `${firstName}_${lastName}.png`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        closeDownloadModal();
      };
    }
  });

  document.querySelectorAll(".export-dropdown .dropdown-menu li").forEach((item) => {
    item.addEventListener("click", function () {
      const type = this.dataset.export;
      downloadModal.style.display = "flex";
      switch (type) {
        case "pdf":
          modalTitle.textContent = "Download PDF";
          modalMessage.textContent = "Do you want to download the PDF for all employees?";
          confirmBtn.onclick = function () {
            window.location.href = "../php/export-all-pdf.php";
            closeDownloadModal();
          };
          break;
        case "excel":
          modalTitle.textContent = "Download Excel";
          modalMessage.textContent = "Do you want to download the Excel file for all employees?";
          confirmBtn.onclick = function () {
            window.location.href = "../php/export-all-excel.php";
            closeDownloadModal();
          };
          break;
        case "qrcode":
          modalTitle.textContent = "Download QR Codes";
          modalMessage.textContent = "Do you want to download the QR codes PDF for all employees?";
          confirmBtn.onclick = function () {
            window.location.href = "../php/export-all-qr.php";
            closeDownloadModal();
          };
          break;
      }
    });
  });