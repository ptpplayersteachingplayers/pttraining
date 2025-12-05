<?php
/**
 * Trainer Profile Template - TeachMe.to Style v5.1
 * Nuclear CSS version with #ptp-app wrapper
 */

defined('ABSPATH') || exit;

// Get trainer ID from URL
$trainer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Sample trainer data - replace with database lookup
$trainer = [
    'id' => 1,
    'name' => 'Marcus Johnson',
    'photo' => 'https://ptpsummercamps.com/wp-content/uploads/2025/12/BG7A1787.jpg',
    'price' => 95,
    'location' => 'Radnor, PA',
    'rating' => 5.0,
    'reviews' => 48,
    'score' => 98,
    'badges' => ['mls'],
    'bio' => 'Former MLS player with 8 years of professional experience. Specialized in technical skills development, finishing, and tactical awareness. I believe in building confidence through proven methodology and personalized training programs.',
    'specialties' => ['Ball Mastery', 'Finishing', '1v1 Skills', 'First Touch', 'Weak Foot Development', 'Game IQ'],
    'experience' => '8 years professional',
    'ages' => '6-18',
    'sessions_completed' => 312
];
?>

<div class="ptp-profile-wrap">
<div id="ptp-app" class="ptp-profile">
    
    <!-- Profile Content -->
    <div class="ptp-profile-content">
        
        <!-- Profile Header -->
        <div class="ptp-profile-header">
            <div class="ptp-profile-photo-wrap">
                <img src="<?php echo esc_url($trainer['photo']); ?>" alt="<?php echo esc_attr($trainer['name']); ?>" class="ptp-profile-photo">
            </div>
            
            <div class="ptp-profile-info">
                <p class="ptp-profile-cat">Private Soccer Training With</p>
                <h1 class="ptp-profile-name">
                    <?php echo esc_html($trainer['name']); ?>
                    <span class="ptp-verified">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </span>
                </h1>
                <p class="ptp-profile-price">
                    From <strong>$<?php echo esc_html($trainer['price']); ?></strong>/session
                    <a href="#packages">View packages</a>
                </p>
                
                <!-- Score Card -->
                <div class="ptp-score-card">
                    <div class="ptp-score-circle">
                        <span class="num"><?php echo esc_html($trainer['score']); ?></span>
                        <span class="lbl">Score</span>
                    </div>
                    <div class="ptp-score-info">
                        <h4>Happy Athlete Score</h4>
                        <p>
                            Based on
                            <?php echo esc_html($trainer['reviews']); ?> reviews,
                            <?php echo esc_html($trainer['sessions_completed']); ?> completed sessions,
                            and response time.
                        </p>
                    </div>
                </div>
                
                <!-- CTA Buttons -->
                <div class="ptp-cta-btns">
                    <a href="#availability" class="ptp-btn ptp-btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                        Check Availability
                    </a>
                    <button type="button" class="ptp-btn ptp-btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        Message
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Bio Section -->
        <section class="ptp-section">
            <h2>About <?php echo esc_html(explode(' ', $trainer['name'])[0]); ?></h2>
            <p style="font-size: 16px !important; line-height: 1.7 !important; color: #4B5563 !important;">
                <?php echo esc_html($trainer['bio']); ?>
            </p>
        </section>
        
        <!-- Specialties Section -->
        <section class="ptp-section">
            <h2>Training Specialties</h2>
            <div class="ptp-tags">
                <?php foreach ($trainer['specialties'] as $specialty): ?>
                <span class="ptp-tag">
                    <span class="ptp-tag-icon">⚽</span>
                    <?php echo esc_html($specialty); ?>
                </span>
                <?php endforeach; ?>
            </div>
        </section>
        
        <!-- Availability Section -->
        <section class="ptp-availability" id="availability">
            <h2>Check Availability</h2>
            <p>Select a training location and time that works for you</p>
            
            <div class="ptp-availability-grid">
                <!-- Locations -->
                <div class="ptp-locations-col">
                    <h3 style="font-size: 14px !important; font-weight: 700 !important; color: #0E0F11 !important; margin-bottom: 16px !important;">Training Locations</h3>
                    <div class="ptp-locations">
                        <div class="ptp-location-card active">
                            <img src="https://ptpsummercamps.com/wp-content/uploads/2025/12/BG7A1520.jpg" alt="Radnor Memorial Park">
                            <div class="ptp-location-content">
                                <h4>Radnor Memorial Park</h4>
                                <p>Radnor, PA • <span class="free">Free parking</span></p>
                            </div>
                        </div>
                        <div class="ptp-location-card">
                            <img src="https://ptpsummercamps.com/wp-content/uploads/2025/12/BG7A1393.jpg" alt="Wayne Sports Complex">
                            <div class="ptp-location-content">
                                <h4>Wayne Sports Complex</h4>
                                <p>Wayne, PA • Indoor available</p>
                            </div>
                        </div>
                        <div class="ptp-location-card">
                            <img src="https://ptpsummercamps.com/wp-content/uploads/2025/12/BG7A1356.jpg" alt="Your Location">
                            <div class="ptp-location-content">
                                <h4>Your Location</h4>
                                <p>+$15 travel fee may apply</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Time Slots -->
                <div class="ptp-times-col">
                    <h3 style="font-size: 14px !important; font-weight: 700 !important; color: #0E0F11 !important; margin-bottom: 16px !important;">Available Times — This Week</h3>
                    <div class="ptp-time-slots">
                        <button type="button" class="ptp-time-slot">Mon 4:00 PM</button>
                        <button type="button" class="ptp-time-slot">Mon 5:30 PM</button>
                        <button type="button" class="ptp-time-slot selected">Tue 4:00 PM</button>
                        <button type="button" class="ptp-time-slot">Tue 5:30 PM</button>
                        <button type="button" class="ptp-time-slot">Wed 4:00 PM</button>
                        <button type="button" class="ptp-time-slot">Thu 4:00 PM</button>
                        <button type="button" class="ptp-time-slot">Thu 5:30 PM</button>
                        <button type="button" class="ptp-time-slot">Fri 4:00 PM</button>
                        <button type="button" class="ptp-time-slot">Sat 9:00 AM</button>
                        <button type="button" class="ptp-time-slot">Sat 10:30 AM</button>
                        <button type="button" class="ptp-time-slot">Sat 12:00 PM</button>
                        <button type="button" class="ptp-time-slot">Sun 10:00 AM</button>
                    </div>
                </div>
                
                <!-- Book Card -->
                <div class="ptp-book-card">
                    <h3>Book Your Session</h3>
                    <div class="ptp-book-summary">
                        <div class="ptp-book-row">
                            <span class="label">Location</span>
                            <span class="value">Radnor Memorial Park</span>
                        </div>
                        <div class="ptp-book-row">
                            <span class="label">Date & Time</span>
                            <span class="value">Tue, Dec 10 @ 4:00 PM</span>
                        </div>
                        <div class="ptp-book-row">
                            <span class="label">Duration</span>
                            <span class="value">60 minutes</span>
                        </div>
                        <div class="ptp-book-row ptp-book-total">
                            <span class="label">Total</span>
                            <span class="value">$<?php echo esc_html($trainer['price']); ?></span>
                        </div>
                    </div>
                    <a href="<?php echo home_url('/checkout/'); ?>?trainer=<?php echo $trainer['id']; ?>" class="ptp-btn-book">
                        Continue to Book
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                    </a>
                    <p class="ptp-guarantee">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        100% satisfaction guarantee
                    </p>
                </div>
            </div>
        </section>
        
        <!-- Reviews Section -->
        <section class="ptp-section">
            <h2>Reviews (<?php echo esc_html($trainer['reviews']); ?>)</h2>
            <div style="display: flex !important; align-items: center !important; gap: 16px !important; margin-bottom: 28px !important;">
                <div style="font-size: 48px !important; font-weight: 900 !important; color: #0E0F11 !important;">5.0</div>
                <div>
                    <div style="color: #FCB900 !important; font-size: 24px !important; letter-spacing: 2px !important;">★★★★★</div>
                    <div style="color: #6B7280 !important; font-size: 14px !important;"><?php echo esc_html($trainer['reviews']); ?> reviews</div>
                </div>
            </div>
            
            <!-- Sample Reviews -->
            <div style="display: flex !important; flex-direction: column !important; gap: 24px !important;">
                <div style="background: #F9FAFB !important; border-radius: 16px !important; padding: 24px !important; border: 1px solid #E5E7EB !important;">
                    <div style="display: flex !important; align-items: center !important; gap: 12px !important; margin-bottom: 12px !important;">
                        <div style="width: 48px !important; height: 48px !important; background: #FCB900 !important; border-radius: 50% !important; display: flex !important; align-items: center !important; justify-content: center !important; font-weight: 800 !important; font-size: 18px !important;">MK</div>
                        <div>
                            <div style="font-weight: 700 !important; color: #0E0F11 !important;">Michael K.</div>
                            <div style="font-size: 13px !important; color: #6B7280 !important;">Parent of Jake (12)</div>
                        </div>
                        <div style="margin-left: auto !important; color: #FCB900 !important;">★★★★★</div>
                    </div>
                    <p style="color: #4B5563 !important; font-size: 15px !important; line-height: 1.6 !important;">"Marcus is incredible with kids. Jake's confidence has skyrocketed after just 4 sessions. He breaks down complex skills into manageable steps and keeps training fun while being productive."</p>
                </div>
                
                <div style="background: #F9FAFB !important; border-radius: 16px !important; padding: 24px !important; border: 1px solid #E5E7EB !important;">
                    <div style="display: flex !important; align-items: center !important; gap: 12px !important; margin-bottom: 12px !important;">
                        <div style="width: 48px !important; height: 48px !important; background: #2563EB !important; color: #FFFFFF !important; border-radius: 50% !important; display: flex !important; align-items: center !important; justify-content: center !important; font-weight: 800 !important; font-size: 18px !important;">SR</div>
                        <div>
                            <div style="font-weight: 700 !important; color: #0E0F11 !important;">Sarah R.</div>
                            <div style="font-size: 13px !important; color: #6B7280 !important;">Parent of Emma (14)</div>
                        </div>
                        <div style="margin-left: auto !important; color: #FCB900 !important;">★★★★★</div>
                    </div>
                    <p style="color: #4B5563 !important; font-size: 15px !important; line-height: 1.6 !important;">"We've tried several trainers and Marcus is by far the best. His MLS experience really shows — he knows exactly what scouts look for and tailors training accordingly. Emma made her club's top team!"</p>
                </div>
            </div>
        </section>
        
    </div>
    
    <!-- Mobile Fixed CTA -->
    <div class="ptp-mobile-cta">
        <div class="ptp-mobile-price">
            From
            <strong>$<?php echo esc_html($trainer['price']); ?></strong>
        </div>
        <a href="#availability" class="ptp-btn ptp-btn-primary">Check Availability</a>
    </div>
    
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Location card selection
    const locationCards = document.querySelectorAll('.ptp-location-card');
    locationCards.forEach(card => {
        card.addEventListener('click', function() {
            locationCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Time slot selection
    const timeSlots = document.querySelectorAll('.ptp-time-slot');
    timeSlots.forEach(slot => {
        slot.addEventListener('click', function() {
            timeSlots.forEach(s => s.classList.remove('selected'));
            this.classList.add('selected');
        });
    });
});
</script>
