// Frenchie Friends - Main JavaScript

// Default dogs data
const DEFAULT_DOGS = [
    {
        id: 1,
        name: "Bruno",
        breed: "French Bulldog",
        type: "Standard",
        color: "Brindle",
        age: 3,
        gender: "Male",
        weight: "12 kg",
        personality: "Playful, friendly, loves belly rubs",
        owner: "John Smith",
        image: "https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?w=400&h=400&fit=crop",
        date_added: "2024-01-15"
    },
    {
        id: 2,
        name: "Luna",
        breed: "French Bulldog",
        type: "Mini",
        color: "Fawn",
        age: 2,
        gender: "Female",
        weight: "10 kg",
        personality: "Gentle, affectionate, loves cuddles",
        owner: "Sarah Johnson",
        image: "https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=400&h=400&fit=crop",
        date_added: "2024-02-20"
    },
    {
        id: 3,
        name: "Winston",
        breed: "French Bulldog",
        type: "Standard",
        color: "Cream",
        age: 4,
        gender: "Male",
        weight: "13 kg",
        personality: "Calm, loyal, enjoys naps",
        owner: "Michael Brown",
        image: "https://images.unsplash.com/photo-1603123853880-a92fafb7809f?w=400&h=400&fit=crop",
        date_added: "2024-03-10"
    },
    {
        id: 4,
        name: "Bella",
        breed: "French Bulldog",
        type: "Standard",
        color: "Pied",
        age: 1,
        gender: "Female",
        weight: "9 kg",
        personality: "Energetic, curious, loves toys",
        owner: "Emily Davis",
        image: "https://images.unsplash.com/photo-1599839575945-a9e5af0c3fa5?w=400&h=400&fit=crop",
        date_added: "2024-04-05"
    },
    {
        id: 5,
        name: "Gus",
        breed: "French Bulldog",
        type: "Standard",
        color: "Blue",
        age: 5,
        gender: "Male",
        weight: "14 kg",
        personality: "Chill, friendly, loves walks",
        owner: "David Wilson",
        image: "https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=400&h=400&fit=crop",
        date_added: "2024-05-12"
    },
    {
        id: 6,
        name: "Coco",
        breed: "French Bulldog",
        type: "Mini",
        color: "Chocolate",
        age: 2,
        gender: "Female",
        weight: "8 kg",
        personality: "Sweet, playful, loves treats",
        owner: "Lisa Anderson",
        image: "https://images.unsplash.com/photo-1537151608828-ea2b11777ee8?w=400&h=400&fit=crop",
        date_added: "2024-06-18"
    }
];

// Default events data
const DEFAULT_EVENTS = [
    {
        id: 1,
        title: "Frenchie Meetup at Central Park",
        type: "meetup",
        date: "2024-07-15",
        time: "10:00 AM",
        location: "Duomo, Milan Area",
        description: "Join us for a fun morning with fellow French Bulldog owners!",
        created_by: "admin",
        date_created: "2025-06-01"
    },
    {
        id: 2,
        title: "Bruno's Birthday Party",
        type: "birthday",
        date: "2024-08-20",
        time: "2:00 PM",
        location: "Doggy Daycare Center",
        description: "Bruno is turning 4! Come celebrate with treats and games.",
        created_by: "admin",
        date_created: "2025-07-10"
    }
];

// Initialize data in localStorage
function initializeData() {
    if (!localStorage.getItem('frenchie_dogs')) {
        localStorage.setItem('frenchie_dogs', JSON.stringify(DEFAULT_DOGS));
    }
    if (!localStorage.getItem('frenchie_events')) {
        localStorage.setItem('frenchie_events', JSON.stringify(DEFAULT_EVENTS));
    }
}

// Get default events (for admin page)
function getDefaultEvents() {
    return DEFAULT_EVENTS;
}

// Load and display dogs
document.addEventListener('DOMContentLoaded', function() {
    // Add Dog Button with SweetAlert
    const addDogBtn = document.getElementById('addDogBtn');
    const addDogForm = document.getElementById('addDogForm');
    const cancelAddDog = document.getElementById('cancelAddDog');

    if (addDogBtn && addDogForm) {
        addDogBtn.addEventListener('click', function() {
            // Show SweetAlert confirmation
            Swal.fire({
                title: 'Add Your Frenchie! 🐶',
                text: 'Would you like to add your French Bulldog to our community?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#8B5CF6',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, add my dog!',
                cancelButtonText: 'Maybe later'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show the form
                    addDogForm.classList.add('show');
                    addDogBtn.style.display = 'none';
                    
                    // Scroll to form
                    addDogForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    
                    Swal.fire({
                        title: 'Great! 🎉',
                        text: 'Fill in the form below to add your Frenchie!',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        });
    }

    // Cancel Add Dog
    if (cancelAddDog && addDogForm && addDogBtn) {
        cancelAddDog.addEventListener('click', function() {
            addDogForm.classList.remove('show');
            addDogBtn.style.display = 'inline-flex';
            
            // Reset form
            const form = document.getElementById('dogForm');
            if (form) {
                form.reset();
            }
            
            // Reset file preview
            if (fileName) fileName.textContent = 'No file chosen';
            if (imagePreview) imagePreview.style.display = 'none';
            if (previewImg) previewImg.src = '';
        });
    }

    // Handle file input change (show preview)
    const imageInput = document.getElementById('image');
    const fileName = document.getElementById('fileName');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                fileName.textContent = file.name;
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                fileName.textContent = 'No file chosen';
                imagePreview.style.display = 'none';
            }
        });
    }

    // Handle dog form submission
    const dogForm = document.getElementById('dogForm');
    if (dogForm) {
        dogForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Get the image file
            const imageFile = document.getElementById('image').files[0];
            let imageBase64 = '';
            
            // Convert image to base64 if file exists
            if (imageFile) {
                imageBase64 = await readFileAsBase64(imageFile);
            } else {
                // Use default image
                imageBase64 = 'https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?w=400&h=400&fit=crop';
            }
            
            const newDog = {
                id: Date.now(),
                name: document.getElementById('name').value,
                breed: 'French Bulldog',
                type: document.getElementById('type').value,
                color: document.getElementById('color').value,
                age: parseInt(document.getElementById('age').value) || 0,
                gender: document.getElementById('gender').value,
                weight: document.getElementById('weight').value,
                personality: document.getElementById('personality').value,
                owner: document.getElementById('owner').value,
                image: imageBase64,
                date_added: new Date().toISOString().split('T')[0]
            };
            
            // Save to localStorage
            let dogs = JSON.parse(localStorage.getItem('frenchie_dogs') || '[]');
            dogs.push(newDog);
            localStorage.setItem('frenchie_dogs', JSON.stringify(dogs));
            
            // Show success message
            const successMsg = document.getElementById('successMessage');
            if (successMsg) {
                successMsg.style.display = 'block';
                setTimeout(() => {
                    successMsg.style.display = 'none';
                }, 3000);
            }
            
            // Reset form and hide
            dogForm.reset();
            fileName.textContent = 'No file chosen';
            imagePreview.style.display = 'none';
            addDogForm.classList.remove('show');
            addDogBtn.style.display = 'inline-flex';
            
            // Reload dogs grid
            loadDogs();
            
            Swal.fire({
                title: 'Success! 🎉',
                text: `${newDog.name} has been added to our community!`,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }
    
    // Helper function to read file as base64
    function readFileAsBase64(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => resolve(reader.result);
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }

    // Video Carousel
    initCarousel();
});

// Load dogs into the grid
function loadDogs() {
    const dogGrid = document.getElementById('dogGrid');
    if (!dogGrid) return;
    
    let dogs = JSON.parse(localStorage.getItem('frenchie_dogs') || '[]');
    
    // Add default dogs if none exist
    if (dogs.length === 0) {
        dogs = DEFAULT_DOGS;
        localStorage.setItem('frenchie_dogs', JSON.stringify(dogs));
    }
    
    dogGrid.innerHTML = dogs.map(dog => `
        <div class="dog-card">
            <img src="${escapeHtml(dog.image)}" alt="${escapeHtml(dog.name)}" class="dog-image">
            <div class="dog-info">
                <h3 class="dog-name">${escapeHtml(dog.name)}</h3>
                <p class="dog-breed">${escapeHtml(dog.breed)} - ${escapeHtml(dog.type)}</p>
                <div class="dog-details">
                    <div class="dog-detail">
                        <span class="dog-detail-icon">🎨</span>
                        <span>${escapeHtml(dog.color)}</span>
                    </div>
                    <div class="dog-detail">
                        <span class="dog-detail-icon">🎂</span>
                        <span>${dog.age} years</span>
                    </div>
                    <div class="dog-detail">
                        <span class="dog-detail-icon">${dog.gender === 'Male' ? '♂️' : '♀️'}</span>
                        <span>${escapeHtml(dog.gender)}</span>
                    </div>
                    <div class="dog-detail">
                        <span class="dog-detail-icon">⚖️</span>
                        <span>${escapeHtml(dog.weight)}</span>
                    </div>
                </div>
                <p class="dog-personality">"${escapeHtml(dog.personality)}"</p>
                <p class="dog-owner">👤 Owner: ${escapeHtml(dog.owner)}</p>
            </div>
        </div>
    `).join('');
}

// Load events into the list
function loadEvents() {
    const eventsList = document.getElementById('eventsList');
    if (!eventsList) return;
    
    let events = JSON.parse(localStorage.getItem('frenchie_events') || '[]');
    
    // Add default events if none exist
    if (events.length === 0) {
        events = DEFAULT_EVENTS;
        localStorage.setItem('frenchie_events', JSON.stringify(events));
    }
    
    eventsList.innerHTML = events.map(event => {
        const eventDate = new Date(event.date);
        const day = eventDate.getDate().toString().padStart(2, '0');
        const month = eventDate.toLocaleString('en-US', { month: 'short' });
        
        return `
            <div class="event-card">
                <div class="event-date">
                    <div class="event-date-day">${day}</div>
                    <div class="event-date-month">${month}</div>
                </div>
                <div class="event-info">
                    <h3 class="event-title">${escapeHtml(event.title)}</h3>
                    <div class="event-meta">
                        <span>📍 ${escapeHtml(event.location)}</span>
                        <span>🕐 ${escapeHtml(event.time)}</span>
                    </div>
                    <span class="event-type ${event.type}">${event.type}</span>
                    <p style="margin-top: 0.5rem; color: var(--text-light);">${escapeHtml(event.description)}</p>
                </div>
            </div>
        `;
    }).join('');
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Carousel Functionality
function initCarousel() {
    const track = document.getElementById('carouselTrack');
    const dotsContainer = document.getElementById('carouselDots');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    if (!track || !dotsContainer) return;
    
    const slides = track.querySelectorAll('.carousel-slide');
    const totalSlides = slides.length;
    let currentSlide = 0;
    
    if (totalSlides === 0) return;
    
    // Create dots
    dotsContainer.innerHTML = '';
    for (let i = 0; i < totalSlides; i++) {
        const dot = document.createElement('button');
        dot.className = 'carousel-dot' + (i === 0 ? ' active' : '');
        dot.addEventListener('click', () => goToSlide(i));
        dotsContainer.appendChild(dot);
    }
    
    const dots = dotsContainer.querySelectorAll('.carousel-dot');
    
    function goToSlide(index) {
        currentSlide = index;
        track.style.transform = `translateX(-${currentSlide * 100}%)`;
        
        // Update dots
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === currentSlide);
        });
    }
    
    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        goToSlide(currentSlide);
    }
    
    function prevSlide() {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        goToSlide(currentSlide);
    }
    
    // Button events
    if (prevBtn) {
        prevBtn.addEventListener('click', prevSlide);
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', nextSlide);
    }
    
    // Auto-play
    let autoPlay = setInterval(nextSlide, 8000);
    
    // Pause on hover
    track.addEventListener('mouseenter', () => clearInterval(autoPlay));
    track.addEventListener('mouseleave', () => {
        autoPlay = setInterval(nextSlide, 8000);
    });
    
    // Touch/swipe support
    let touchStartX = 0;
    let touchEndX = 0;
    
    track.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });
    
    track.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });
    
    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                nextSlide();
            } else {
                prevSlide();
            }
        }
    }
}

// Smooth scroll for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
