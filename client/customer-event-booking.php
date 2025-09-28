<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Event Booking</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="main">
  <div class="head-title">
    <h1>Book an Event</h1>
  </div>

  <div class="container">
    <div class="card calendar-wrapper">
      <section class="calendar-main">

        <!-- Calendar Controls -->
      <header class="calendar-header">
        <div class="calendar-controls-left">
          <button id="add-event" class="btn-add">
            <i class="material-icons">add</i> Book Event
          </button>
          <button id="go-today" class="btn small go-today">Today</button>
          <select id="year-select" class="btn small">
            <!-- Options will be populated via JS -->
          </select>
        </div>

        <div class="calendar-controls-month">
          <button id="prev-month" class="btn small arrow">&lt;</button>
          <span id="calendar-month" class="month-name">August</span>
          <button id="next-month" class="btn small arrow">&gt;</button>
        </div>
      </header>

        <!-- Weekday Labels -->
        <div class="weekdays">
          <span>Mon</span>
          <span>Tue</span>
          <span>Wed</span>
          <span>Thu</span>
          <span>Fri</span>
          <span>Sat</span>
          <span>Sun</span>
        </div>

        <!-- Dates Grid -->
        <div id="dates" class="dates"></div>
      </section>
    </div>
  </div>

  <!-- Customer Booking Modal -->
  <?php include '../includes/customer-event-modal.php'; ?>
</div>

<script src="../js/customer-event.js"></script>
</body>
</html>
