document.addEventListener("DOMContentLoaded", () => {
    const accountBtn = document.getElementById("accountBtn");
    const closeModalBtn = document.getElementById("closeModalBtn");
    const saveModalBtn = document.getElementById("saveModalBtn");
    const currentPassword = document.getElementById("currentPassword");
    const newPassword = document.getElementById("newPassword");
    const confirmPassword = document.getElementById("confirmPassword");
    const alertText = document.getElementById("alert-text");

    if (!accountBtn || !closeModalBtn || !saveModalBtn || !currentPassword || !newPassword || !confirmPassword) return;

    // Reusable alert function
    async function showAlert(message, isSuccess = false) {
        if (!message) return false;

        alertText.textContent = message;
        alertText.style.display = "block";
        alertText.style.color = isSuccess ? "green" : "red";

        if (!isSuccess) {
            setTimeout(() => {
                alertText.style.display = "none";
            }, 3000);
        }

        return isSuccess;
    }

    accountBtn.addEventListener("click", () => {
        const modal = document.getElementById("accountModal");
        if (modal) {
            modal.classList.add("show-modal");
            modal.classList.remove("hide-modal");
        }

        currentPassword.value = "";
        newPassword.value = "";
        confirmPassword.value = "";
    });

    closeModalBtn.addEventListener("click", () => {
        const modal = document.getElementById("accountModal");
        if (modal) {
            modal.classList.remove("show-modal");
            modal.classList.add("hide-modal");
        }
    });

    saveModalBtn.addEventListener("click", async () => {
        const modal = document.getElementById("accountModal");

        // Validation
        if (currentPassword.value === "" || newPassword.value === "" || confirmPassword.value === "") {
            return showAlert("All fields are required.");
        } else if (newPassword.value.length < 8) {
            return showAlert("New password must be at least 8 characters long.");
        } else if (newPassword.value !== confirmPassword.value) {
            return showAlert("New password and confirmation do not match.");
        }

        try {
            const response = await fetch("../php/change-password.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams({
                    currentPassword: currentPassword.value,
                    newPassword: newPassword.value,
                    confirmPassword: confirmPassword.value,
                }),
            });

            const result = await response.json();

            if (result.status === "success") {
                await showAlert(result.message, true);

                setTimeout(() => {
                    if (modal) {
                        modal.classList.remove("show-modal");
                        modal.classList.add("hide-modal");
                    }
                }, 2000);
            } else {
                showAlert(result.message);
            }
        } catch (error) {
            console.error("Fetch error:", error);
            showAlert("⚠️ Something went wrong. Please try again.");
        }
    });
});
