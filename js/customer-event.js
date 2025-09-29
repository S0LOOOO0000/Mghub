// customer-event.js
document.addEventListener("DOMContentLoaded", () => {
  // ===== Elements =====
  const calendarMonthEl = document.getElementById("calendar-month");
  const datesEl = document.getElementById("calendar-dates");
  const prevMonthBtn = document.getElementById("prev-month");   // ✅ fixed
  const nextMonthBtn = document.getElementById("next-month");   // ✅ fixed
  const goTodayBtn = document.getElementById("btn-go-today");
  const yearSelect = document.getElementById("select-year");
  const addEventBtn = document.getElementById("btn-add-event");
  const addModal = document.getElementById("customerEventModal");
  const addForm = document.getElementById("customerEventForm");
  const closeBtn = addModal.querySelector(".close-btn");

  let events = [];
  const currentDate = new Date();
  let selectedDate = new Date(currentDate);

  // ===== Helpers =====
  const isoDate = d =>
    `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, "0")}-${String(d.getDate()).padStart(2, "0")}`;

  const isSameDate = (a, b) =>
    a.getFullYear() === b.getFullYear() &&
    a.getMonth() === b.getMonth() &&
    a.getDate() === b.getDate();

  const showPopup = (msg, type = "success") => {
    if (!msg) return;
    const popup = document.createElement("div");
    popup.className = type === "success" ? "success-popup" : "error-popup";
    popup.textContent = msg;
    document.body.appendChild(popup);
    requestAnimationFrame(() => popup.style.opacity = "1");
    setTimeout(() => {
      popup.style.opacity = "0";
      setTimeout(() => popup.remove(), 500);
    }, 3000);
  };

  // ===== Fetch Events =====
  const fetchEvents = async () => {
    try {
      const res = await fetch("../php/get-events.php", { cache: "no-store" });
      events = await res.json();
    } catch (err) {
      events = [];
      console.error("Error fetching events:", err);
    }
  };

  // ===== Render Calendar =====
  const renderCalendar = () => {
    datesEl.innerHTML = "";
    const year = selectedDate.getFullYear();
    const month = selectedDate.getMonth();

    calendarMonthEl.textContent = selectedDate.toLocaleString("default", { month: "long" });

    const firstDay = (new Date(year, month, 1).getDay() + 6) % 7; // Monday-first
    const lastDate = new Date(year, month + 1, 0).getDate();

    // Empty slots before start
    for (let i = 0; i < firstDay; i++) {
      const blank = document.createElement("div");
      blank.className = "empty";
      datesEl.appendChild(blank);
    }

    // Fill dates
    for (let d = 1; d <= lastDate; d++) {
      const dateObj = new Date(year, month, d);
      const dayIso = isoDate(dateObj);

      const div = document.createElement("div");
      div.className = "date-cell";
      div.textContent = d;

      if (isSameDate(dateObj, currentDate)) div.classList.add("today");
      if (isSameDate(dateObj, selectedDate)) div.classList.add("selected");

      const dayEvents = events.filter(ev => ev.event_date === dayIso);
      if (dayEvents.some(ev => ["Booked", "Completed"].includes(ev.event_status))) {
        div.classList.add("has-event");
        if (dayEvents.some(e => e.event_status === "Booked")) div.classList.add("booked");
        if (dayEvents.some(e => e.event_status === "Completed")) div.classList.add("completed");
        div.title = dayEvents.map(e => `${e.event_name} (${e.event_status})`).join("\n");
      }

      div.addEventListener("click", () => {
        selectedDate = dateObj;
        renderCalendar();
      });

      datesEl.appendChild(div);
    }
  };

  // ===== Controls =====
  prevMonthBtn.addEventListener("click", async () => {
    selectedDate.setMonth(selectedDate.getMonth() - 1);
    await refresh();
  });

  nextMonthBtn.addEventListener("click", async () => {
    selectedDate.setMonth(selectedDate.getMonth() + 1);
    await refresh();
  });

  goTodayBtn.addEventListener("click", async () => {
    selectedDate = new Date(currentDate);
    if (yearSelect) yearSelect.value = selectedDate.getFullYear();
    await refresh();
  });

  // Year select
  if (yearSelect) {
    const thisYear = currentDate.getFullYear();
    for (let y = thisYear - 5; y <= thisYear + 5; y++) {
      const opt = document.createElement("option");
      opt.value = y;
      opt.textContent = y;
      if (y === selectedDate.getFullYear()) opt.selected = true;
      yearSelect.appendChild(opt);
    }
    yearSelect.addEventListener("change", async () => {
      selectedDate.setFullYear(parseInt(yearSelect.value));
      await refresh();
    });
  }

  // ===== Add Event Button =====
  addEventBtn.addEventListener("click", e => {
    e.preventDefault();
    const dayIso = isoDate(selectedDate);
    const today = new Date(); today.setHours(0, 0, 0, 0);
    const selDate = new Date(selectedDate); selDate.setHours(0, 0, 0, 0);

    if (selDate <= today) {
      showPopup("You cannot book past or same-day events.", "error");
      return;
    }

    const diffDays = Math.floor((selDate - today) / (1000 * 60 * 60 * 24));
    if (diffDays < 1) {
      showPopup("Events must be booked at least 1 day in advance.", "error");
      return;
    }

    const dayEvents = events.filter(ev => ev.event_date === dayIso);
    const hasBooked = dayEvents.some(ev => ev.event_status === "Booked");
    const allCancelled = dayEvents.length && dayEvents.every(ev => ev.event_status === "Cancelled");
    if (hasBooked && !allCancelled) {
      showPopup("This date already has a booked event.", "error");
      return;
    }

    addForm.reset();
    addForm.querySelector('[name="event_date"]').value = dayIso;
    addModal.classList.add("show");
  });

  // ===== Modal Close =====
  closeBtn.addEventListener("click", () => addModal.classList.remove("show"));
  window.addEventListener("click", e => {
    if (e.target === addModal) addModal.classList.remove("show");
  });

  // ===== Submit Booking (Pending) =====
  addForm.addEventListener("submit", async e => {
    e.preventDefault();
    try {
      const res = await fetch("../php/customer-add-event.php", {
        method: "POST",
        body: new FormData(addForm)
      });

      const data = await res.json();

      if (data.status === "success") {
        showPopup(data.message || "Booking submitted! Pending approval.", "success");
        addModal.classList.remove("show");
        await refresh();
      } else {
        showPopup(data.message || "Failed to submit booking.", "error");
      }
    } catch (err) {
      console.error("Booking error:", err);
      showPopup("Error while submitting booking.", "error");
    }
  });

  // ===== Refresh =====
  const refresh = async () => {
    await fetchEvents();
    renderCalendar();
  };

  refresh();
});
