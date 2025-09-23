/* event.js - Calendar, CRUD, preview modal, delete, filters */
document.addEventListener("DOMContentLoaded", () => {
  // ---------- Elements ----------
  const weekdayEl = document.getElementById("weekday");
  const dayEl = document.getElementById("day");
  const monthEl = document.getElementById("month");
  const datesEl = document.getElementById("dates");
  const calendarMonthEl = document.getElementById("calendar-month");

  const prevMonthBtn = document.getElementById("prev-month");
  const nextMonthBtn = document.getElementById("next-month");
  const goTodayBtn = document.getElementById("go-today");

  const addEventBtn = document.getElementById("add-event");
  const eventListEl = document.getElementById("event-list");
  const noEventsEl = document.getElementById("no-events");
  const filterBtns = document.querySelectorAll(".filter-buttons button");

  const addModal = document.getElementById("addEventModal");
  const editModal = document.getElementById("editEventModal");
  const previewModal = document.getElementById("previewEventModal");

  const addForm = document.getElementById("addEventForm");
  const editForm = document.getElementById("editEventForm");
  const searchInput = document.querySelector('.form-input input[type="search"]');
  const yearSelect = document.getElementById("year-select");

  // Delete modal
  const eventDeleteModal = document.getElementById("eventDeleteModal");
  const eventDeleteForm = document.getElementById("eventDeleteForm");
  const eventDeleteText = document.getElementById("eventDeleteText");
  const closeEventDeleteModal = document.getElementById("closeEventDeleteModal");
  const cancelEventDeleteBtn = document.getElementById("cancelEventDeleteBtn");

  // ---------- State ----------
  let events = [];
  const currentDate = new Date();
  let selectedDate = new Date(currentDate);
  let currentFilter = "all";

  // ---------- Helpers ----------
  const isoDate = d => d.toISOString().split("T")[0];
  const isSameDate = (a, b) =>
    a.getFullYear() === b.getFullYear() &&
    a.getMonth() === b.getMonth() &&
    a.getDate() === b.getDate();

  const escapeHtml = str =>
    str ? String(str).replace(/[&<>"]/g, c => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;" }[c])) : "";

  const formatTime12 = t => {
    if (!t) return "";
    const [h, m] = t.split(":").map(Number);
    const ampm = h >= 12 ? "PM" : "AM";
    const h12 = h % 12 || 12;
    return `${h12}:${m.toString().padStart(2, "0")} ${ampm}`;
  };

  const showPopup = (msg, type = "success") => {
    if (!msg) return;
    const popup = document.createElement("div");
    popup.className = type === "success" ? "success-popup" : "error-popup";
    popup.textContent = msg;
    document.body.appendChild(popup);
    requestAnimationFrame(() => (popup.style.opacity = "1"));
    setTimeout(() => {
      popup.style.opacity = "0";
      setTimeout(() => popup.remove(), 500);
    }, 3000);
  };

  const parseServerResponse = text => {
    try {
      const obj = JSON.parse(text);
      if (obj && typeof obj === "object") {
        return { ok: obj.status?.toLowerCase() === "success" || obj.success === true, message: obj.message || text };
      }
    } catch (e) {}
    const trimmed = (text || "").trim();
    return { ok: trimmed.toLowerCase().includes("success"), message: trimmed };
  };

  // ---------- Fetch events ----------
  const fetchEvents = async () => {
    try {
      const res = await fetch("../php/get-events.php", { cache: "no-store" });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const data = await res.json();
      events = Array.isArray(data) ? data : [];
    } catch (err) {
      events = [];
      console.error(err);
      showPopup("Error loading events", "error");
    }
  };

  // ---------- Render ----------
  const renderHeader = () => {
    weekdayEl.textContent = selectedDate.toLocaleDateString("en-US", { weekday: "long" });
    dayEl.textContent = selectedDate.getDate();
    monthEl.textContent = selectedDate.toLocaleString("default", { month: "long" });
    calendarMonthEl.textContent = selectedDate.toLocaleString("default", { month: "long" });
  };

  const renderDates = () => {
    datesEl.innerHTML = "";
    const year = selectedDate.getFullYear();
    const month = selectedDate.getMonth();
    const firstDay = (new Date(year, month, 1).getDay() + 6) % 7;
    const lastDate = new Date(year, month + 1, 0).getDate();

    // Empty slots
    for (let i = 0; i < firstDay; i++) {
      const blank = document.createElement("div");
      blank.className = "empty";
      datesEl.appendChild(blank);
    }

    for (let d = 1; d <= lastDate; d++) {
      const dateObj = new Date(year, month, d);
      const dayIso = isoDate(dateObj);
      const div = document.createElement("div");
      div.className = "date-cell";
      div.textContent = d;

      if (isSameDate(dateObj, currentDate)) div.classList.add("today");
      if (isSameDate(dateObj, selectedDate)) div.classList.add("selected");

      const dayEvents = events.filter(ev => ev.event_date === dayIso);
      if (dayEvents.length) {
        div.classList.add("has-event");

        // Unique statuses only
        const statuses = [...new Set(dayEvents.map(e => e.event_status))];

        const dotWrap = document.createElement("div");
        dotWrap.className = "event-dots";

        statuses.forEach(status => {
          const dot = document.createElement("span");
          dot.className = "dot";
          if (status === "Booked") dot.style.background = "green";
          if (status === "Completed") dot.style.background = "blue";
          if (status === "Cancelled") dot.style.background = "red";
          dotWrap.appendChild(dot);
        });

        div.appendChild(dotWrap);
        div.title = dayEvents.map(e => `${e.event_name} (${e.event_status})`).join("\n");
      }

      div.addEventListener("click", () => {
        selectedDate = dateObj;
        renderHeader();
        renderDates();
        // reset to ALL filter when clicking a date
        currentFilter = "all";
        filterBtns.forEach(b => b.classList.remove("active"));
        document.querySelector('[data-filter="all"]')?.classList.add("active");
        renderEvents(true);
      });

      datesEl.appendChild(div);
    }
  };

  const renderEvents = (filterByDay = false) => {
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    // Auto-complete past booked events
    events.forEach(ev => {
      const evDate = new Date(ev.event_date + " " + (ev.event_time || "00:00"));
      if (ev.event_status === "Booked" && evDate < today) ev.event_status = "Completed";
    });

    let filtered = [...events];
    if (filterByDay) {
      // always show all for selected date
      filtered = filtered.filter(ev => ev.event_date === isoDate(selectedDate));
    } else if (currentFilter !== "all") {
      filtered = filtered.filter(ev => (ev.event_status || "").trim().toLowerCase() === currentFilter);
    }

    const searchTerm = searchInput?.value.trim().toLowerCase();
    if (searchTerm) filtered = filtered.filter(ev => (ev.event_name || "").toLowerCase().includes(searchTerm));

    // Sort
    const order = { booked: 1, completed: 2, cancelled: 3 };
    filtered.sort((a, b) => {
      const aStatus = (a.event_status || "").toLowerCase();
      const bStatus = (b.event_status || "").toLowerCase();
      const aDate = new Date(a.event_date + " " + (a.event_time || "00:00"));
      const bDate = new Date(b.event_date + " " + (b.event_time || "00:00"));
      const s = (order[aStatus] || 99) - (order[bStatus] || 99);
      return s !== 0 ? s : aDate - bDate;
    });

    eventListEl.innerHTML = "";
    noEventsEl.style.display = filtered.length ? "none" : "block";

    filtered.forEach(ev => {
      const li = document.createElement("li");
      li.className = "event-item";
      li.innerHTML = `
        <div class="event-content">
          <strong>${escapeHtml(ev.event_name)}</strong><br>
          <span class="muted small">${escapeHtml(ev.customer_name)} • <strong>${escapeHtml(ev.event_status)}</strong></span><br>
          <span class="muted small">${new Date(ev.event_date).toLocaleDateString("en-US",{month:"long",day:"numeric",year:"numeric"})} • ${escapeHtml(formatTime12(ev.event_time))}</span>
        </div>
        <div class="event-actions">
          <button class="btn small preview-btn" data-id="${ev.event_id}" type="button"><span class="material-icons">visibility</span></button>
          <button class="btn small edit-btn" data-id="${ev.event_id}" type="button"><span class="material-icons">edit</span></button>
          <button class="btn small danger delete-btn" data-id="${ev.event_id}" type="button"><span class="material-icons">delete</span></button>
        </div>
      `;

      // Preview
      li.querySelector(".preview-btn")?.addEventListener("click", e => {
        e.stopPropagation();
        ["customer_name","customer_email","customer_contact","event_name","event_date","event_time","event_description"].forEach(f=>{
          const el=document.getElementById(`preview_${f}`);
          if(el) el.textContent = f==="event_time"?formatTime12(ev[f]):ev[f]||"";
        });
        const titleEl=document.getElementById("preview_event_name");
        if(titleEl) titleEl.textContent=ev.event_name||"";
        const statusEl=document.getElementById("preview_event_status");
        if(statusEl){
          statusEl.textContent=ev.event_status||"";
          statusEl.className="";
          if(ev.event_status==="Booked") statusEl.classList.add("status-booked");
          if(ev.event_status==="Completed") statusEl.classList.add("status-completed");
          if(ev.event_status==="Cancelled") statusEl.classList.add("status-cancelled");
        }
        openModal(previewModal);
      });

      // Edit
      li.querySelector(".edit-btn")?.addEventListener("click", e => {
        e.stopPropagation();
        if (!editForm) return;
        ["event_id","customer_name","customer_email","customer_contact","event_name","event_date","event_time","event_description","event_status"].forEach(f=>{
          const el=editForm.querySelector(`[name="${f}"]`);
          if(el) el.value=ev[f]||"";
        });
        const emailSwitch=document.getElementById("edit_send_email");
        if(emailSwitch) emailSwitch.checked=false;
        openModal(editModal);
      });

      // Delete
      li.querySelector(".delete-btn")?.addEventListener("click", e => {
        e.stopPropagation();
        if (!eventDeleteForm) return;
        eventDeleteForm.querySelector('input[name="event_id"]').value = ev.event_id;
        eventDeleteText.textContent = `Are you sure you want to delete the event "${ev.event_name}"?`;
        openModal(eventDeleteModal);
      });

      eventListEl.appendChild(li);
    });
  };

  // ---------- Modals ----------
  const openModal = m => m?.classList.add("show");
  const closeModal = m => m?.classList.remove("show");
  document.querySelectorAll(".close-btn").forEach(btn => btn.addEventListener("click", () => closeModal(btn.closest(".modal"))));
  document.querySelectorAll(".modal").forEach(m => m.addEventListener("click", e => { if (e.target === m) closeModal(m); }));

  // ---------- Forms ----------
  addForm?.addEventListener("submit", async e => {
    e.preventDefault();
    const eventDateStr = addForm.querySelector('[name="event_date"]').value;
    const eventDate = new Date(eventDateStr);
    eventDate.setHours(0, 0, 0, 0);

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    // Rule 1: At least 1 day in advance
    const minAllowed = new Date(today);
    minAllowed.setDate(today.getDate() + 1);
    if (eventDate < minAllowed) {
      showPopup("Events must be booked at least 1 day in advance.", "error");
      return;
    }

    // Rule 2: No double booking unless all cancelled
    const dayEvents = events.filter(ev => ev.event_date === eventDateStr);
    const hasBooked = dayEvents.some(ev => ev.event_status === "Booked");
    const allCancelled = dayEvents.length && dayEvents.every(ev => ev.event_status === "Cancelled");
    if (hasBooked && !allCancelled) {
      showPopup("There's already a booked event on this day.", "error");
      return;
    }

    try {
      const res = await fetch("../php/add-event.php", { method: "POST", body: new FormData(addForm) });
      const r = parseServerResponse(await res.text());
      if (r.ok) {
        showPopup("Event added successfully!", "success");
        closeModal(addModal);
        await refresh();
      } else showPopup("Failed: " + r.message, "error");
    } catch (err) {
      console.error(err);
      showPopup("Network error while adding event.", "error");
    }
  });

  editForm?.addEventListener("submit", async e => {
    e.preventDefault();
    const formData = new FormData(editForm);
    const sendEmailCheckbox = document.getElementById("edit_send_email");
    formData.set("send_email", sendEmailCheckbox ? (sendEmailCheckbox.checked ? 1 : 0) : 0);
    try {
      const res = await fetch("../php/edit-event.php", { method: "POST", body: formData });
      const r = parseServerResponse(await res.text());
      if (r.ok) {
        showPopup("Event updated successfully!", "success");
        closeModal(editModal);
        await refresh();
      } else showPopup("Failed: " + r.message, "error");
    } catch (err) {
      console.error(err);
      showPopup("Network error while updating event.", "error");
    }
  });

  eventDeleteForm?.addEventListener("submit", async e => {
    e.preventDefault();
    const eventId = eventDeleteForm.querySelector('input[name="event_id"]').value;
    if (!eventId) return;
    try {
      const res = await fetch("../php/delete-event.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "event_id=" + encodeURIComponent(eventId)
      });
      const text = await res.text();
      if (text.trim() === "success") {
        showPopup("Event deleted successfully!", "success");
        await refresh();
      } else showPopup("Failed to delete: " + text, "error");
    } catch (err) {
      console.error(err);
      showPopup("Error deleting event.", "error");
    } finally {
      closeModal(eventDeleteModal);
    }
  });

  closeEventDeleteModal?.addEventListener("click", () => closeModal(eventDeleteModal));
  cancelEventDeleteBtn?.addEventListener("click", () => closeModal(eventDeleteModal));

  // ---------- Filters & nav ----------
  filterBtns.forEach(btn =>
    btn.addEventListener("click", async () => {
      filterBtns.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
      currentFilter = btn.dataset.filter || "all";
      await refresh();
    })
  );

  prevMonthBtn?.addEventListener("click", async () => {
    selectedDate.setMonth(selectedDate.getMonth() - 1);
    await refresh();
  });
  nextMonthBtn?.addEventListener("click", async () => {
    selectedDate.setMonth(selectedDate.getMonth() + 1);
    await refresh();
  });
  goTodayBtn?.addEventListener("click", async () => {
    selectedDate = new Date(currentDate);
    await refresh();
  });

  searchInput?.addEventListener("input", () => renderEvents());

  if (yearSelect) {
    const currentYear = currentDate.getFullYear();
    for (let y = currentYear - 5; y <= currentYear + 5; y++) {
      const opt = document.createElement("option");
      opt.value = y;
      opt.textContent = y;
      if (y === selectedDate.getFullYear()) opt.selected = true;
      yearSelect.appendChild(opt);
    }
    yearSelect.addEventListener("change", async () => {
      const y = parseInt(yearSelect.value);
      if (!isNaN(y)) {
        selectedDate.setFullYear(y);
        await refresh();
      }
    });
  }

  // ---------- Add Event Button ----------
  if (addEventBtn && addModal && addForm) {
    addEventBtn.addEventListener("click", e => {
      e.preventDefault();

      const dayIso = isoDate(selectedDate);
      const dayEvents = events.filter(ev => ev.event_date === dayIso);

      const today = new Date();
      today.setHours(0, 0, 0, 0);

      const selDate = new Date(selectedDate);
      selDate.setHours(0, 0, 0, 0);

      // 🚫 Past date check
      if (selDate < today) {
        showPopup("You cannot add events to past dates.", "error");
        return;
      }

      // 🚫 Must be at least 1 day in advance
      const diffDays = Math.floor((selDate - today) / (1000 * 60 * 60 * 24));
      if (diffDays < 1) {
        showPopup("Events must be booked at least 1 day in advance.", "error");
        return;
      }

      // 🚫 Already booked
      const hasBooked = dayEvents.some(ev => ev.event_status === "Booked");
      const allCancelled = dayEvents.length && dayEvents.every(ev => ev.event_status === "Cancelled");

      if (hasBooked && !allCancelled) {
        showPopup("There's already a booked event on this day.", "error");
        return;
      }

      addForm.reset();
      addForm.querySelector('[name="event_date"]').value = dayIso;
      openModal(addModal);
    });
  }

  // ---------- Refresh ----------
  const refresh = async () => {
    await fetchEvents();
    renderHeader();
    renderDates();
    renderEvents();
  };

  // ---------- Init ----------
  refresh();
});
