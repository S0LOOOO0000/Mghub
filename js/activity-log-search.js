document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.querySelector(".log-search");
    const tableRows = document.querySelectorAll("#inventoryLogsTable tbody tr");

    searchInput.addEventListener("keyup", function () {
        const query = this.value.toLowerCase();

        tableRows.forEach(row => {
            const rowText = row.innerText.toLowerCase();

            // Show row if it contains the search text
            if (rowText.includes(query)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
});