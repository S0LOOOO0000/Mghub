<!-- Export Modal -->
<div id="exportModal" class="modal">
  <div class="modal-content">
    <h3>Export Attendance</h3>
    <label for="exportType">Export Type:</label>
    <select id="exportType">
      <option value="excel">Whole Month (Excel)</option>
      <option value="pdf">Individual (PDF)</option>
    </select>

    <div id="filtersExcel">
      <label>Month:</label>
      <select id="monthSelectExcel"> ... </select>
      <label>Year:</label>
      <select id="yearSelectExcel"> ... </select>
    </div>

    <div id="filtersPDF" style="display:none;">
      <label>Employee:</label>
      <select id="employeeSelect"> ... </select>
      <label>Month:</label>
      <select id="monthSelectPDF"> ... </select>
      <label>Year:</label>
      <select id="yearSelectPDF"> ... </select>
    </div>

    <button id="exportBtn">Export</button>
  </div>
</div>
