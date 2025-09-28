<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Event Booking</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css"> <!-- global styles -->
  <link rel="stylesheet" href="../css/customer-event.css"> <!-- dedicated calendar CSS -->
</head>
<body>

<div class="main">
  <div class="head-title">
    <h1>Book an Event</h1>
  </div>

  <div class="customer-container">
    <div class="customer-card">
      <section class="customer-calendar-main">

        <!-- Calendar Header -->
        <header class="customer-calendar-header">
          <div class="customer-calendar-controls-left">
            <button id="add-event" class="btn-add">
              <i class="material-icons">add</i> Book Event
            </button>
            <button id="go-today" class="btn small go-today">Today</button>
            <select id="year-select" class="btn small"></select>
          </div>

          <div class="customer-calendar-controls-month">
            <button id="prev-month" class="customer-arrow">&lt;</button>
            <span id="customer-calendar-month" class="month-name">August</span>
            <button id="next-month" class="customer-arrow">&gt;</button>
          </div>
        </header>

        <!-- Weekday Labels -->
        <div class="customer-weekdays">
          <span>Mon</span>
          <span>Tue</span>
          <span>Wed</span>
          <span>Thu</span>
          <span>Fri</span>
          <span>Sat</span>
          <span>Sun</span>
        </div>

        <!-- Dates Grid -->
        <div id="customer-dates" class="customer-dates"></div>

      </section>
    </div>
  </div>

  <!-- Customer Booking Modal -->
  <?php include '../includes/customer-event-modal.php'; ?>
</div>

<script src="../js/customer-event.js"></script>
</body>
</html>
