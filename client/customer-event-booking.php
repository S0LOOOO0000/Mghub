<?php
// customer-event-booking.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Event Booking</title>
  <?php include '../includes/favicon.php'; ?>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/components/form2.css">
  <link rel="stylesheet" href="../css/components/alerts.css">
</head>
<body>

<div class="main">
  <div class="head-title"><h1>Book an Event</h1></div>

  <div class="container">
    <div class="card calendar-wrapper">
      <section class="calendar-main">
        <!-- Calendar Controls -->
        <header class="calendar-header">
          <div class="calendar-controls-left">
            <button id="btn-add-event" class="btn-add">
              <i class="material-icons">add</i> Book Event
            </button>
            <button id="btn-go-today" class="btn small go-today">Today</button>
            <select id="select-year" class="btn small"></select>
          </div>

          <div class="calendar-controls-month">
            <button id="btn-prev-month" class="btn small arrow">&lt;</button>
            <span id="calendar-month" class="month-name">August</span>
            <button id="btn-next-month" class="btn small arrow">&gt;</button>
          </div>
        </header>

        <div class="weekdays">
          <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span>
          <span>Fri</span><span>Sat</span><span>Sun</span>
        </div>

        <div id="calendar-dates" class="dates"></div>
      </section>
    </div>
  </div>

  <!-- Modal -->
  <?php include '../includes/customer-event-modal.php'; ?>
</div>

<script src="../js/customer-event.js"></script>
</body>
</html>
