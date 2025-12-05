<?php
/**
 * Marketplace Template - TeachMe.to Style v5.1
 * Uses ptp-marketplace-wrap for full-width breakout
 */

defined('ABSPATH') || exit;

// Get filter values
$location = isset($_GET['location']) ? sanitize_text_field($_GET['location']) : '';
$specialty = isset($_GET['specialty']) ? sanitize_text_field($_GET['specialty']) : '';
$coach_type = isset($_GET['coach_type']) ? sanitize_text_field($_GET['coach_type']) : '';
?>

<div class="ptp-marketplace-wrap">
    
    <!-- Promo Banner -->
    <div class="ptp-promo-banner">
        🎄 Holiday Special: First session FREE for new athletes! 
        <a href="#trainers">Book Now →</a>
    </div>
    
    <!-- Header -->
    <header class="ptp-header">
        <div class="ptp-header-inner">
            <a href="<?php echo home_url(); ?>" class="ptp-logo">
                <span class="ptp-logo-icon">
                    <img src="https://ptpsummercamps.com/wp-content/uploads/2023/07/ptp-logo.png" alt="PTP" onerror="this.style.display='none';this.parentNode.innerHTML='⚽';">
                </span>
                <span>PTP Training</span>
            </a>
            
            <div class="ptp-header-search">
                <div class="ptp-search-segment">
                    <span class="ptp-search-label">Train Soccer</span>
                    <span class="ptp-search-value">
                        Private Training
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"></path></svg>
                    </span>
                </div>
                <div class="ptp-search-segment">
                    <span class="ptp-search-label">Near</span>
                    <span class="ptp-search-value">
                        <?php echo $location ? esc_html($location) : 'Enter location'; ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"></path></svg>
                    </span>
                </div>
            </div>
            
            <div class="ptp-header-actions">
                <a href="<?php echo home_url('/trainer-application/'); ?>" class="ptp-btn-become">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" x2="19" y1="8" y2="14"></line><line x1="22" x2="16" y1="11" y2="11"></line></svg>
                    Become a Trainer
                </a>
                <button type="button" class="ptp-menu-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"></line><line x1="4" x2="20" y1="6" y2="6"></line><line x1="4" x2="20" y1="18" y2="18"></line></svg>
                </button>
            </div>
        </div>
    </header>
    
    <!-- Hero Section -->
    <section class="ptp-hero" style="background-image: url('https://ptpsummercamps.com/wp-content/uploads/2025/12/BG7A1915.jpg');">
        <div class="ptp-hero-content">
            <h1 class="ptp-hero-title">
                DON'T JUST DREAM.
                <span>TRAIN.</span>
            </h1>
            <p class="ptp-hero-subtitle">1-on-1 private soccer training with MLS players and NCAA D1 athletes</p>
            
            <div class="ptp-hero-search">
                <div class="ptp-hero-search-inner">
                    <span class="ptp-hero-search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                    </span>
                    <input type="text" id="ptp-location-input" placeholder="Enter your ZIP code or city" value="<?php echo esc_attr($location); ?>">
                    <button type="button" class="ptp-hero-gps" id="ptp-gps-btn" title="Use my location">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M12 2v4m0 12v4M2 12h4m12 0h4"></path></svg>
                    </button>
                    <button type="button" class="ptp-hero-search-btn" id="ptp-search-btn">Find Trainers</button>
                </div>
            </div>
            
            <div class="ptp-hero-trust">
                <p>Our coaches have played for:</p>
                <div class="ptp-trust-logos">
                    <span class="ptp-trust-item">MLS</span>
                    <span class="ptp-trust-item">NCAA D1</span>
                    <span class="ptp-trust-item">USSF Licensed</span>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Filters Bar -->
    <div class="ptp-filters-bar">
        <div class="ptp-filters-inner">
            <span class="ptp-filter-label">Filter</span>
            
            <button type="button" class="ptp-filter-btn" data-filter="coach_type">
                Coach Type
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"></path></svg>
            </button>
            
            <button type="button" class="ptp-filter-btn" data-filter="location">
                Location
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"></path></svg>
            </button>
            
            <button type="button" class="ptp-filter-btn" data-filter="specialty">
                Specialty
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"></path></svg>
            </button>
            
            <button type="button" class="ptp-filter-btn" data-filter="price">
                Price
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"></path></svg>
            </button>
            
            <button type="button" class="ptp-filter-btn ptp-filter-active" data-filter="mls">
                MLS Coaches
            </button>
            
            <div class="ptp-filter-divider"></div>
            
            <div class="ptp-sort-wrap">
                <span class="ptp-filter-label">Sort</span>
                <select id="ptp-sort">
                    <option value="recommended">Recommended</option>
                    <option value="price_low">Price: Low to High</option>
                    <option value="price_high">Price: High to Low</option>
                    <option value="rating">Highest Rated</option>
                    <option value="distance">Nearest</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Main Layout -->
    <div class="ptp-main-layout" id="trainers">
        <!-- Results Section -->
        <div class="ptp-results-section">
            <div class="ptp-results-header">
                <h1>Private Soccer Trainers Near You</h1>
                <p id="ptp-results-count">Finding trainers in your area...</p>
            </div>
            
            <div class="ptp-trainers-grid" id="ptp-trainers-grid">
                <!-- Loading State -->
                <div class="ptp-loading-state" id="ptp-loading">
                    <div class="ptp-spinner"></div>
                    <p>Finding trainers near you...</p>
                </div>
            </div>
        </div>
        
        <!-- Map Section -->
        <div class="ptp-map-section">
            <div class="ptp-map-container" id="ptp-map">
                <!-- Map will be initialized here -->
            </div>
        </div>
    </div>
    
    <!-- Mobile CTA -->
    <div class="ptp-mobile-cta">
        <button type="button" class="ptp-mobile-cta-btn" id="ptp-mobile-map-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            View Map
        </button>
    </div>
    
</div>

<!-- Trainer Card Template -->
<template id="ptp-card-template">
    <a href="#" class="ptp-trainer-card" data-trainer-id="">
        <div class="ptp-card-image">
            <img src="" alt="">
            <div class="ptp-card-badges"></div>
            <div class="ptp-card-score">
                <span class="score-number">96</span>
                <span class="score-label">Score</span>
            </div>
            <div class="ptp-card-overlay">
                <div class="ptp-card-info">
                    <h3>
                        <span class="trainer-name"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#3B82F6"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-2 15-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                    </h3>
                    <p class="ptp-card-price">From <strong>$85</strong>/session</p>
                </div>
                <span class="ptp-card-arrow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                </span>
            </div>
        </div>
        <div class="ptp-card-meta">
            <div class="ptp-meta-row">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <span class="trainer-location"></span>
            </div>
            <div class="ptp-meta-row">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                <span class="ptp-stars">★★★★★</span>
                <strong class="trainer-rating">5.0</strong>
                <span class="trainer-reviews">(48 reviews)</span>
            </div>
            <div class="ptp-meta-row">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                <span class="ptp-availability">Available this week</span>
            </div>
        </div>
    </a>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const grid = document.getElementById('ptp-trainers-grid');
    const loading = document.getElementById('ptp-loading');
    const template = document.getElementById('ptp-card-template');
    const resultsCount = document.getElementById('ptp-results-count');
    
    // Sample trainer data - replace with API call
    const trainers = [
        {
            id: 1,
            name: 'Marcus Johnson',
            photo: 'https://ptpsummercamps.com/wp-content/uploads/2025/12/BG7A1787.jpg',
            price: 95,
            location: 'Radnor, PA',
            rating: 5.0,
            reviews: 48,
            score: 98,
            badges: ['mls'],
            available: true
        },
        {
            id: 2,
            name: 'Tyler Rodriguez',
            photo: 'https://ptpsummercamps.com/wp-content/uploads/2025/12/BG7A1730.jpg',
            price: 85,
            location: 'Wayne, PA',
            rating: 4.9,
            reviews: 36,
            score: 96,
            badges: ['d1'],
            available: true
        },
        {
            id: 3,
            name: 'Chris Williams',
            photo: 'https://ptpsummercamps.com/wp-content/uploads/2025/12/BG7A1642.jpg',
            price: 90,
            location: 'King of Prussia, PA',
            rating: 5.0,
            reviews: 52,
            score: 97,
            badges: ['mls', 'super'],
            available: true
        },
        {
            id: 4,
            name: 'Jordan Smith',
            photo: 'https://ptpsummercamps.com/wp-content/uploads/2025/12/BG7A1596.jpg',
            price: 80,
            location: 'Bryn Mawr, PA',
            rating: 4.8,
            reviews: 29,
            score: 94,
            badges: ['d1'],
            available: true
        },
        {
            id: 5,
            name: 'Alex Chen',
            photo: 'https://ptpsummercamps.com/wp-content/uploads/2025/12/BG7A1563.jpg',
            price: 100,
            location: 'Villanova, PA',
            rating: 5.0,
            reviews: 67,
            score: 99,
            badges: ['mls'],
            available: true
        },
        {
            id: 6,
            name: 'David Martinez',
            photo: 'https://ptpsummercamps.com/wp-content/uploads/2025/12/BG7A1539.jpg',
            price: 75,
            location: 'Ardmore, PA',
            rating: 4.9,
            reviews: 41,
            score: 95,
            badges: ['d1'],
            available: true
        }
    ];
    
    function createBadgeHTML(badge) {
        switch(badge) {
            case 'mls':
                return '<span class="ptp-badge-mls">⚽ MLS PRO</span>';
            case 'd1':
                return '<span class="ptp-badge-d1">🎓 NCAA D1</span>';
            case 'super':
                return '<span class="ptp-badge-supercoach">🏆 SuperCoach</span>';
            default:
                return '';
        }
    }
    
    function renderTrainers(data) {
        // Clear loading
        if (loading) loading.style.display = 'none';
        
        // Update count
        if (resultsCount) resultsCount.textContent = `${data.length} trainers available near you`;
        
        // Render cards
        data.forEach(trainer => {
            const clone = template.content.cloneNode(true);
            const card = clone.querySelector('.ptp-trainer-card');
            
            card.href = `<?php echo home_url('/trainer/'); ?>?id=${trainer.id}`;
            card.dataset.trainerId = trainer.id;
            
            const img = card.querySelector('.ptp-card-image img');
            img.src = trainer.photo;
            img.alt = trainer.name;
            
            // Badges
            const badgesContainer = card.querySelector('.ptp-card-badges');
            badgesContainer.innerHTML = trainer.badges.map(createBadgeHTML).join('');
            
            // Score
            const scoreNum = card.querySelector('.ptp-card-score .score-number');
            scoreNum.textContent = trainer.score;
            
            // Name
            const nameSpan = card.querySelector('.trainer-name');
            nameSpan.textContent = trainer.name;
            
            // Price
            const priceEl = card.querySelector('.ptp-card-price strong');
            priceEl.textContent = `$${trainer.price}`;
            
            // Location
            const locationEl = card.querySelector('.trainer-location');
            locationEl.textContent = trainer.location;
            
            // Rating
            const ratingEl = card.querySelector('.trainer-rating');
            ratingEl.textContent = trainer.rating.toFixed(1);
            
            // Reviews
            const reviewsEl = card.querySelector('.trainer-reviews');
            reviewsEl.textContent = `(${trainer.reviews} reviews)`;
            
            grid.appendChild(card);
        });
    }
    
    // Simulate loading
    setTimeout(() => {
        renderTrainers(trainers);
    }, 800);
    
    // Search button
    const searchBtn = document.getElementById('ptp-search-btn');
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            const location = document.getElementById('ptp-location-input').value;
            if (location) {
                window.location.href = `<?php echo home_url('/find-trainers/'); ?>?location=${encodeURIComponent(location)}`;
            }
        });
    }
    
    // Enter key on input
    const locationInput = document.getElementById('ptp-location-input');
    if (locationInput) {
        locationInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('ptp-search-btn').click();
            }
        });
    }
    
    // GPS button
    const gpsBtn = document.getElementById('ptp-gps-btn');
    if (gpsBtn) {
        gpsBtn.addEventListener('click', function() {
            if (navigator.geolocation) {
                this.style.color = '#2563EB';
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        document.getElementById('ptp-location-input').value = 'Current Location';
                    },
                    function(error) {
                        alert('Unable to get your location. Please enter it manually.');
                    }
                );
            }
        });
    }
    
    // Initialize Google Map if available
    if (typeof google !== 'undefined' && google.maps) {
        const mapContainer = document.getElementById('ptp-map');
        if (mapContainer) {
            const map = new google.maps.Map(mapContainer, {
                center: { lat: 40.0379, lng: -75.3499 }, // Radnor, PA
                zoom: 11,
                styles: [
                    { featureType: "poi", stylers: [{ visibility: "off" }] }
                ]
            });
            
            // Add markers for each trainer
            trainers.forEach(trainer => {
                // Would need geocoded coordinates here
            });
        }
    }
});
</script>
