document.addEventListener("DOMContentLoaded", () => {
  const monthSelect = document.getElementById("monthSelect");
  const yearSelect = document.getElementById("yearSelect");
  const headRow = document.getElementById("monthlyAttendanceHead");
  const body = document.getElementById("monthlyAttendanceBody");

  function loadAttendance() {
    const month = monthSelect.value;
    const year = yearSelect.value;

    fetch(`../php/get-attendance-monthly.php?month=${month}&year=${year}`)
      .then(res => res.text())
      .then(data => {
        const daysInMonth = new Date(year, month, 0).getDate();
        // Header
        headRow.innerHTML = "<th>Employee Name</th>";
        for (let d = 1; d <= daysInMonth; d++) {
          headRow.innerHTML += `<th>${d}</th>`;
        }
        // Add Total columns
        ["P","L","A","C","LV"].forEach(status => {
          headRow.innerHTML += `<th>${status}</th>`;
        });

        body.innerHTML = data;
      })
      .catch(err => console.error(err));
  }

  monthSelect.addEventListener("change", loadAttendance);
  yearSelect.addEventListener("change", loadAttendance);
  loadAttendance();

  // --- Export Modal ---
  const exportModal = document.getElementById('exportModal');
  const openBtn = document.getElementById('openExportModal');
  const closeBtn = document.getElementById('closeExportModal');
  const exportType = document.getElementById('exportType');
  const filtersExcel = document.getElementById('filtersExcel');
  const filtersPDF = document.getElementById('filtersPDF');

  openBtn.addEventListener('click', () => exportModal.classList.add('active'));
  closeBtn.addEventListener('click', () => exportModal.classList.remove('active'));
  exportModal.addEventListener('click', e => { if(e.target===exportModal) exportModal.classList.remove('active'); });

  exportType.addEventListener('change', e => {
    filtersExcel.style.display = e.target.value==='excel'?'block':'none';
    filtersPDF.style.display = e.target.value==='pdf'?'block':'none';
  });

  document.getElementById('exportBtn').addEventListener('click', () => {
    const type = exportType.value;
    if(type==='excel'){
      const month = document.getElementById('monthSelectExcel').value;
      const year = document.getElementById('yearSelectExcel').value;
      window.open(`../php/export-attendance-excel.php?month=${month}&year=${year}`,'_blank');
    } else {
      const emp = document.getElementById('employeeSelect').value;
      const month = document.getElementById('monthSelectPDF').value;
      const year = document.getElementById('yearSelectPDF').value;
      window.open(`../php/export-attendance-pdf.php?employee=${emp}&month=${month}&year=${year}`,'_blank');
    }
  });
});
