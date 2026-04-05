<?php
session_start();

// Simple admin authentication (in production, use proper authentication)
$adminPassword = 'jagabam007!#'; // Change this in production!

// Check if admin is logged in
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    if ($_POST['password'] === $adminPassword) {
        $_SESSION['is_admin'] = true;
        $isAdmin = true;
    } else {
        $loginError = 'Invalid password!';
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// Load events data
$eventsJson = file_get_contents('data/events.json');
$eventsData = json_decode($eventsJson, true);
$events = $eventsData['events'] ?? [];

// Handle add event
if ($isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_event'])) {
    $newEvent = [
        'id' => count($events) + 1,
        'title' => $_POST['title'] ?? '',
        'type' => $_POST['type'] ?? 'other',
        'date' => $_POST['date'] ?? '',
        'time' => $_POST['time'] ?? '',
        'location' => $_POST['location'] ?? '',
        'description' => $_POST['description'] ?? '',
        'created_by' => 'admin',
        'date_created' => date('Y-m-d')
    ];
    
    $events[] = $newEvent;
    $eventsData['events'] = $events;
    file_put_contents('data/events.json', json_encode($eventsData, JSON_PRETTY_PRINT));
    
    header('Location: admin.php?success=1');
    exit;
}

// Handle delete event
if ($isAdmin && isset($_GET['delete'])) {
    $eventId = (int)$_GET['delete'];
    $events = array_filter($events, function($e) use ($eventId) {
        return $e['id'] !== $eventId;
    });
    $events = array_values($events);
    $eventsData['events'] = $events;
    file_put_contents('data/events.json', json_encode($eventsData, JSON_PRETTY_PRINT));
    header('Location: admin.php?deleted=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Frenchie Friends</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <span class="logo-icon">🐾</span>
                <span>Frenchie Friends</span>
            </a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <?php if ($isAdmin): ?>
                    <li><span class="admin-badge">👑 Admin</span></li>
                    <li><a href="?logout=1">Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <h1>🔐 Admin Dashboard</h1>
        <p>Manage events, meetups, and birthday parties for our Frenchie community!</p>
    </section>

    <div class="container">
        <?php if (!$isAdmin): ?>
            <!-- Login Form -->
            <div class="form-section login-form">
                <h3 class="form-title">🔑 Admin Login</h3>
                <?php if (isset($loginError)): ?>
                    <div class="message error"><?php echo $loginError; ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required placeholder="Enter admin password">
                    </div>
                    <div class="form-actions">
                        <button type="submit" name="login" class="btn btn-primary">Login</button>
                    </div>
                </form>
                <p style="margin-top: 1rem; text-align: center; color: var(--text-light);">
                    <small>Default password: frenchie123</small>
                </p>
            </div>
        <?php else: ?>
            <!-- Success Messages -->
            <?php if (isset($_GET['success'])): ?>
                <div class="message success">✅ Event added successfully!</div>
            <?php endif; ?>
            <?php if (isset($_GET['deleted'])): ?>
                <div class="message success">✅ Event deleted successfully!</div>
            <?php endif; ?>

            <!-- Add Event Form -->
            <div class="form-section">
                <h3 class="form-title">📅 Add New Event</h3>
                <form method="POST" action="">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="title">Event Title *</label>
                            <input type="text" id="title" name="title" required placeholder="e.g., Frenchie Meetup">
                        </div>
                        <div class="form-group">
                            <label for="type">Event Type</label>
                            <select id="type" name="type">
                                <option value="meetup">Meetup</option>
                                <option value="birthday">Birthday Party</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date">Date *</label>
                            <input type="date" id="date" name="date" required>
                        </div>
                        <div class="form-group">
                            <label for="time">Time *</label>
                            <input type="time" id="time" name="time" required>
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label for="location">Location *</label>
                            <input type="text" id="location" name="location" required placeholder="e.g., Central Park, New York">
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" placeholder="Describe the event..."></textarea>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" name="add_event" class="btn btn-success">
                            <span>📅</span> Add Event
                        </button>
                    </div>
                </form>
            </div>

            <!-- Manage Events -->
            <div class="form-section">
                <h3 class="form-title">📝 Manage Events</h3>
                <div class="events-list">
                    <?php if (empty($events)): ?>
                        <p style="text-align: center; color: var(--text-light);">No events yet. Add your first event above!</p>
                    <?php else: ?>
                        <?php foreach ($events as $event): ?>
                            <?php 
                                $eventDate = new DateTime($event['date']);
                                $day = $eventDate->format('d');
                                $month = $eventDate->format('M');
                            ?>
                            <div class="event-card">
                                <div class="event-date">
                                    <div class="event-date-day"><?php echo $day; ?></div>
                                    <div class="event-date-month"><?php echo $month; ?></div>
                                </div>
                                <div class="event-info">
                                    <h3 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h3>
                                    <div class="event-meta">
                                        <span>📍 <?php echo htmlspecialchars($event['location']); ?></span>
                                        <span>🕐 <?php echo htmlspecialchars($event['time']); ?></span>
                                    </div>
                                    <span class="event-type <?php echo $event['type']; ?>"><?php echo htmlspecialchars($event['type']); ?></span>
                                    <p style="margin-top: 0.5rem; color: var(--text-light);"><?php echo htmlspecialchars($event['description']); ?></p>
                                </div>
                                <a href="?delete=<?php echo $event['id']; ?>" class="btn btn-secondary" onclick="return confirm('Are you sure you want to delete this event?')">
                                    🗑️ Delete
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>🐾 Frenchie Friends &copy; <?php echo date('Y'); ?> - Admin Dashboard</p>
    </footer>
</body>
</html>
