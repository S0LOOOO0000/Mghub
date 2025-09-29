
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Calendar</title>
    <?php include '../includes/favicon.php'; ?>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet" />
</head>
<body>
                <section class="sidebar">
                    <?php include '../includes/admin-sidebar.php'; ?>
                </section>

                <section class="content" id="content">
                        <nav>
                            <!-- Menu Icon -->
                            <i class="material-icons icon-menu">menu</i>
                            <!-- Searchbar -->
                          <form action="#" onsubmit="return false;">
                            <div class="form-input">
                              <input type="search" id="searchEvent" placeholder="Search event name...">
                              <button type="submit" class="search-btn">
                                <i class="material-icons search-icon">search</i>
                              </button>
                            </div>
                          </form>
                            <!-- Notification Bell and Profile -->
                            <?php include '../includes/admin-navbar.php'; ?>
                        </nav>
                    
                        <div class="main">
                                <div class="head-title">
                                    <div class="left">
                                        <h1>Bookings</h1>
                                        <ul class="breadcrumb">
                                            <li> <a>Bookings</a> </li>
                                            <li> <i class='material-icons right-icon'>chevron_right</i></li>
                                            <li> <a class="active">Home</a> </li>
                                        </ul>
                                    </div>
                                </div>

                          <div class="container">
                            <div class="card calendar-wrapper">

                              <!-- Calendar Content -->
                              <div class="calendar-container">
                                
                                <!-- Left Panel: Selected Day + Events -->
                                <aside class="calendar-info">
                                     
                                  <!-- Selected Date -->
                                  <section class="selected-date">
                                    <i class="material-icons calendar-icon">calendar_today</i>
                                    <span class="weekday" id="weekday">Mon</span>
                                    <span class="day" id="day">01</span>
                                    <span class="month" id="month">September</span>
                                  </section>

                                  <!-- Event Management -->
                                  <section class="event-section">
                                    <header class="event-header">
                                    <h4>Event List</h4>
                                    </header>

                                    <!-- Filters -->
                                  <div class="filter-buttons">
                                    <button data-filter="all" class="active">All</button>
                                    <button data-filter="booked">Booked</button>
                                    <button data-filter="completed">Completed</button>
                                    <button data-filter="cancelled">Cancelled</button>
                                  </div>
                                    
                                    <!-- Event List -->
                                    <ul id="event-list" class="event-list"></ul>
                                    <p id="no-events" class="no-events">Event list is empty...</p>
                                  </section>
                                </aside>

                                <!-- Right Panel: Calendar Grid -->
                                <section class="calendar-main">
                                  
                                  <!-- Calendar Controls -->
                                  <header class="calendar-header">
                                      <div class="calendar-controls-left">
                                      <button id="add-event" class="btn-add">
                                        <i class="material-icons">add</i> Add Event
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
                          </div>

                    <?php include '../includes/event-modal.php'; ?>
                    <?php include '../includes/preview-modal.php'; ?>

    </div>
    </section>

	

    <!-- Custom JS -->
   
    <script src="../js/event.js"></script>
    <script src="../js/dashboard.js"></script>
	<script src="../js/employee.js"> </script>
  <script src="../js/export-booking.js"> </script>
  
</body>
</html>