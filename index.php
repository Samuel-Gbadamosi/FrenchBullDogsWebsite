<?php
// Load dogs data
$dogsJson = file_get_contents('data/dogs.json');
$dogsData = json_decode($dogsJson, true);
$dogs = $dogsData['dogs'] ?? [];

// Load events data
$eventsJson = file_get_contents('data/events.json');
$eventsData = json_decode($eventsJson, true);
$events = $eventsData['events'] ?? [];

// Handle form submission for adding a new dog
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_dog'])) {
    $newDog = [
        'id' => count($dogs) + 1,
        'name' => $_POST['name'] ?? '',
        'breed' => 'French Bulldog',
        'type' => $_POST['type'] ?? 'Standard',
        'color' => $_POST['color'] ?? '',
        'age' => (int)($_POST['age'] ?? 0),
        'gender' => $_POST['gender'] ?? '',
        'weight' => $_POST['weight'] ?? '',
        'personality' => $_POST['personality'] ?? '',
        'owner' => $_POST['owner'] ?? '',
        'image' => $_POST['image'] ?: 'https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?w=400&h=400&fit=crop',
        'date_added' => date('Y-m-d')
    ];
    
    $dogs[] = $newDog;
    $dogsData['dogs'] = $dogs;
    file_put_contents('data/dogs.json', json_encode($dogsData, JSON_PRETTY_PRINT));
    
    header('Location: index.php?success=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>French Bulldogs - Meet Our Adorable Friends</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
                <li><a href="#dogs">Our Dogs</a></li>
                <li><a href="#events">Events</a></li>
                <li><a href="#videos">Videos</a></li>
                <li><a href="admin.php">Admin</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <h1>🐶 Welcome to Frenchie Friends! 🐶</h1>
        <p>Discover adorable French Bulldogs, connect with owners, and join fun meetups and events!</p>
    </section>

    <div class="container">
        <!-- Success Message -->
        <?php if (isset($_GET['success'])): ?>
            <div class="message success">
                ✅ Dog added successfully!
            </div>
        <?php endif; ?>

        <!-- Dogs Grid Section -->
        <section id="dogs">
            <div class="section-header">
                <h2 class="section-title">
                    🐕 Our French Bulldogs
                </h2>
                <button class="btn btn-primary" id="addDogBtn">
                    <span>➕</span> Add Your Frenchie
                </button>
            </div>

            <!-- Add Dog Form (Hidden by default) -->
            <div class="form-section hidden-form" id="addDogForm">
                <h3 class="form-title">Add Your French Bulldog</h3>
                <form method="POST" action="">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Name *</label>
                            <input type="text" id="name" name="name" required placeholder="e.g., Bruno">
                        </div>
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select id="type" name="type">
                                <option value="Standard">Standard</option>
                                <option value="Mini">Mini</option>
                                <option value="Teacup">Teacup</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="color">Color</label>
                            <select id="color" name="color">
                                <option value="Brindle">Brindle</option>
                                <option value="Fawn">Fawn</option>
                                <option value="Cream">Cream</option>
                                <option value="Pied">Pied</option>
                                <option value="Blue">Blue</option>
                                <option value="Chocolate">Chocolate</option>
                                <option value="Black">Black</option>
                                <option value="White">White</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="age">Age (years)</label>
                            <input type="number" id="age" name="age" min="0" max="20" placeholder="e.g., 3">
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="weight">Weight</label>
                            <input type="text" id="weight" name="weight" placeholder="e.g., 12 kg">
                        </div>
                        <div class="form-group">
                            <label for="owner">Owner Name *</label>
                            <input type="text" id="owner" name="owner" required placeholder="e.g., John Smith">
                        </div>
                        <div class="form-group">
                            <label for="image">Image URL</label>
                            <input type="url" id="image" name="image" placeholder="https://example.com/dog.jpg">
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label for="personality">Personality</label>
                            <textarea id="personality" name="personality" placeholder="Describe your dog's personality..."></textarea>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" name="add_dog" class="btn btn-success">
                            <span>💾</span> Save Dog
                        </button>
                        <button type="button" class="btn btn-secondary" id="cancelAddDog">
                            <span>❌</span> Cancel
                        </button>
                    </div>
                </form>
            </div>

            <!-- Dogs Grid -->
            <div class="dog-grid">
                <?php foreach ($dogs as $dog): ?>
                    <div class="dog-card">
                        <img src="<?php echo htmlspecialchars($dog['image']); ?>" alt="<?php echo htmlspecialchars($dog['name']); ?>" class="dog-image">
                        <div class="dog-info">
                            <h3 class="dog-name"><?php echo htmlspecialchars($dog['name']); ?></h3>
                            <p class="dog-breed"> <?php echo htmlspecialchars($dog['type']); ?></p>
                            <div class="dog-details">
                                <div class="dog-detail">
                                    <span class="dog-detail-icon">🎨</span>
                                    <span><?php echo htmlspecialchars($dog['color']); ?></span>
                                </div>
                                <div class="dog-detail">
                                    <span class="dog-detail-icon">🎂</span>
                                    <span><?php echo $dog['age']; ?> years</span>
                                </div>
                                <div class="dog-detail">
                                    <span class="dog-detail-icon"><?php echo $dog['gender'] === 'Male' ? '♂️' : '♀️'; ?></span>
                                    <span><?php echo htmlspecialchars($dog['gender']); ?></span>
                                </div>
                                <div class="dog-detail">
                                    <span class="dog-detail-icon">⚖️</span>
                                    <span><?php echo htmlspecialchars($dog['weight']); ?></span>
                                </div>
                            </div>
                            <p class="dog-personality">"<?php echo htmlspecialchars($dog['personality']); ?>"</p>
                            <p class="dog-owner">👤 Owner: <?php echo htmlspecialchars($dog['owner']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Events Section -->
        <section id="events" class="video-section">
            <div class="section-header">
                <h2 class="section-title">
                    📅 Upcoming Events
                </h2>
                <a href="admin.php" class="btn btn-primary">
                    <span>🔐</span> Admin Login
                </a>
            </div>
            
            <div class="events-list">
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
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Videos Section -->
        <section id="videos" class="video-section">
            <div class="section-header">
                <h2 class="section-title">
                    🎥 Frenchie Videos
                </h2>
            </div>
            
            <div class="carousel-container">
                <div class="carousel-track" id="carouselTrack">
                    <div class="carousel-slide">
                        <div class="video-wrapper">
                            <iframe src="https://www.youtube.com/embed/3dcli9i_pvA" title="French Bulldog Video 1" allowfullscreen></iframe>
                        </div>
                        <p style="text-align: center; padding: 1rem; color: var(--text-light);">Funny French Bulldogs Compilation</p>
                    </div>
                    <div class="carousel-slide">
                        <div class="video-wrapper">
                            <iframe src="https://www.youtube.com/embed/8p0gA1m8L3E" title="French Bulldog Video 2" allowfullscreen></iframe>
                        </div>
                        <p style="text-align: center; padding: 1rem; color: var(--text-light);">Cute French Bulldog Puppies</p>
                    </div>
                    <div class="carousel-slide">
                        <div class="video-wrapper">
                            <iframe src="https://www.youtube.com/embed/7Zxm2JtZxpQ" title="French Bulldog Video 3" allowfullscreen></iframe>
                        </div>
                        <p style="text-align: center; padding: 1rem; color: var(--text-light);">French Bulldog Playing</p>
                    </div>
                </div>
                <div class="carousel-controls">
                    <button class="carousel-btn" id="prevBtn">◀</button>
                    <div class="carousel-dots" id="carouselDots"></div>
                    <button class="carousel-btn" id="nextBtn">▶</button>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>🐾 Frenchie Friends &copy; <?php echo date('Y'); ?> - Made with ❤️ for French Bulldogs</p>
    </footer>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
