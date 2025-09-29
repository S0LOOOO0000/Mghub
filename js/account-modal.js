document.addEventListener("DOMContentLoaded", () => {
    const accountBtn = document.getElementById("accountBtn");
    const closeModalBtn = document.getElementById("closeModalBtn");

    if (!accountBtn) return;    
    if (!closeModalBtn) return;

    accountBtn.addEventListener("click", (e) => {
        e.preventDefault();
        const modal = document.getElementById("accountModal");
        if (modal) {
            modal.classList.add("show-modal");
            modal.classList.remove("hide-modal");
        }
    });

    closeModalBtn.addEventListener("click", () => {
        const modal = document.getElementById("accountModal");
        if (modal) {
            modal.classList.remove("show-modal");
            modal.classList.add("hide-modal");
        }
    });
});