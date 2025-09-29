document.addEventListener("DOMContentLoaded", () => {
    const bookingTable = document.getElementById("bookingTable");

    bookingTable.addEventListener("click", async (e) => {
        const btn = e.target.closest(".action-btn");
        if(!btn) return;

        const bookingId = btn.dataset.id;
        const action = btn.classList.contains("approve") ? "approve" : "decline";
        if(!bookingId) return;

        if(confirm(`Are you sure you want to ${action} this booking?`)){
            try {
                const res = await fetch("../php/approve-booking.php", {
                    method: "POST",
                    headers: {"Content-Type": "application/x-www-form-urlencoded"},
                    body: `booking_id=${bookingId}&action=${action}`
                });
                const data = await res.json();
                alert(data.message);
                if(data.status==="success") location.reload();
            } catch(err){
                alert("Error: "+err);
            }
        }
    });
});
