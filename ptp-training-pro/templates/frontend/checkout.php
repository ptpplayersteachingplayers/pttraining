<?php
/**
 * PTP Training Pro - Checkout
 * TeachMe.to-inspired design with PTP branding
 * 
 * @version 5.0.0
 */

if (!defined('ABSPATH')) exit;

// Get checkout data
$trainer_id = isset($_GET['trainer']) ? intval($_GET['trainer']) : 0;
$package_type = isset($_GET['package']) ? sanitize_text_field($_GET['package']) : 'single';
$selected_date = isset($_GET['date']) ? sanitize_text_field($_GET['date']) : '';
$selected_time = isset($_GET['time']) ? sanitize_text_field($_GET['time']) : '';
$location_id = isset($_GET['location']) ? intval($_GET['location']) : 0;

// Get trainer
global $wpdb;
$trainer = null;
if ($trainer_id) {
    $trainer = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}ptp_trainers WHERE id = %d AND status = 'approved'",
        $trainer_id
    ));
}

// Default pricing
$session_price = $trainer ? floatval($trainer->session_rate) : 75;
$pack_5_price = $session_price * 5 * 0.9;
$pack_10_price = $session_price * 10 * 0.8;

// Get user data if logged in
$current_user = wp_get_current_user();
$is_logged_in = is_user_logged_in();

// Get user's athletes
$athletes = array();
if ($is_logged_in) {
    $athletes = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}ptp_athletes WHERE parent_user_id = %d ORDER BY name",
        $current_user->ID
    ));
}

// Get trainer location
$location = null;
if ($location_id) {
    $location = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}ptp_trainer_locations WHERE id = %d",
        $location_id
    ));
}

// Calculate price based on package
switch ($package_type) {
    case '5pack':
        $total_price = $pack_5_price;
        $package_name = '5-Session Pack';
        $session_count = 5;
        $discount_pct = 10;
        break;
    case '10pack':
        $total_price = $pack_10_price;
        $package_name = '10-Session Pack';
        $session_count = 10;
        $discount_pct = 20;
        break;
    default:
        $total_price = $session_price;
        $package_name = 'Single Session';
        $session_count = 1;
        $discount_pct = 0;
}

$original_price = $session_price * $session_count;
$discount_amount = $original_price - $total_price;

// Average rating
$avg_rating = $trainer ? ($wpdb->get_var($wpdb->prepare(
    "SELECT AVG(rating) FROM {$wpdb->prefix}ptp_reviews WHERE trainer_id = %d AND status = 'approved'",
    $trainer_id
)) ?: 5.0) : 5.0;

$review_count = $trainer ? $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM {$wpdb->prefix}ptp_reviews WHERE trainer_id = %d AND status = 'approved'",
    $trainer_id
)) : 0;

// Stripe public key
$stripe_pk = get_option('ptp_stripe_public_key', '');
$logo_dark_url = 'https://ptpsoccercamps.com/wp-content/uploads/2023/07/ptp-logo.png';
?>

<div class="ptp-checkout-wrap">
<div class="ptp-checkout-v2" id="ptpCheckout">
    
    <!-- Header -->
    <header class="ptp-checkout-header">
        <div class="ptp-checkout-header-inner">
            <a href="<?php echo $trainer ? home_url('/trainer/' . $trainer_id) : home_url('/find-trainers'); ?>" class="ptp-back-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Back to profile
            </a>
            
            <a href="<?php echo home_url(); ?>" class="ptp-logo">
                <div class="ptp-logo-icon">
                    <img src="<?php echo esc_url($logo_dark_url); ?>" alt="PTP" onerror="this.parentElement.innerHTML='⚽'">
                </div>
                <span>PTP Training</span>
            </a>

            <div class="ptp-secure-badge">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                Secure checkout
            </div>
        </div>
    </header>

    <!-- Progress Bar -->
    <div class="ptp-progress-bar">
        <div class="ptp-progress-inner">
            <div class="ptp-progress-step completed">
                <div class="ptp-step-number">✓</div>
                <span>Select</span>
            </div>
            <div class="ptp-progress-line completed"></div>
            <div class="ptp-progress-step active">
                <div class="ptp-step-number">2</div>
                <span>Details</span>
            </div>
            <div class="ptp-progress-line"></div>
            <div class="ptp-progress-step">
                <div class="ptp-step-number">3</div>
                <span>Confirm</span>
            </div>
        </div>
    </div>

    <!-- Main Layout -->
    <main class="ptp-checkout-main">
        
        <!-- Form Section -->
        <div class="ptp-form-section">
            
            <!-- Athlete Selection -->
            <div class="ptp-form-card">
                <div class="ptp-form-card-header">
                    <div class="ptp-form-icon">👤</div>
                    <div>
                        <h2>Who's training?</h2>
                        <p>Select or add the athlete for this session</p>
                    </div>
                </div>

                <div class="ptp-athlete-selector" id="athleteSelector">
                    <?php if (!empty($athletes)): ?>
                        <?php foreach ($athletes as $index => $athlete): ?>
                        <label class="ptp-athlete-option">
                            <input type="radio" name="athlete_id" value="<?php echo $athlete->id; ?>" <?php echo $index === 0 ? 'checked' : ''; ?>>
                            <div class="ptp-athlete-content">
                                <div class="ptp-athlete-avatar"><?php echo strtoupper(substr($athlete->name, 0, 1)); ?></div>
                                <div class="ptp-athlete-info">
                                    <h4><?php echo esc_html($athlete->name); ?></h4>
                                    <p>Age <?php echo esc_html($athlete->age); ?> · <?php echo esc_html(ucfirst($athlete->skill_level ?: 'Beginner')); ?></p>
                                </div>
                                <div class="ptp-athlete-check">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                </div>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <button type="button" class="ptp-add-athlete-btn" id="addAthleteBtn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
                        </svg>
                        Add <?php echo !empty($athletes) ? 'another' : 'an'; ?> athlete
                    </button>
                </div>

                <!-- Add Athlete Form (hidden by default) -->
                <div class="ptp-add-athlete-form" id="addAthleteForm" style="display: none;">
                    <div class="ptp-form-row">
                        <div class="ptp-form-group">
                            <label>Athlete Name</label>
                            <input type="text" name="new_athlete_name" class="ptp-input" placeholder="Enter name">
                        </div>
                        <div class="ptp-form-group">
                            <label>Age</label>
                            <input type="number" name="new_athlete_age" class="ptp-input" placeholder="Age" min="4" max="25">
                        </div>
                    </div>
                    <div class="ptp-form-group">
                        <label>Skill Level</label>
                        <select name="new_athlete_level" class="ptp-input">
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="advanced">Advanced</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Package Selection -->
            <div class="ptp-form-card">
                <div class="ptp-form-card-header">
                    <div class="ptp-form-icon">📦</div>
                    <div>
                        <h2>Choose your package</h2>
                        <p>Save more with multi-session packs</p>
                    </div>
                </div>

                <div class="ptp-package-options">
                    <label class="ptp-package-option">
                        <input type="radio" name="package" value="single" <?php echo $package_type === 'single' ? 'checked' : ''; ?>>
                        <div class="ptp-package-content">
                            <div class="ptp-package-radio"></div>
                            <div class="ptp-package-info">
                                <h4>Single Session</h4>
                                <p>Try it out first</p>
                            </div>
                            <div class="ptp-package-price">
                                <div class="ptp-price">$<?php echo number_format($session_price); ?></div>
                                <div class="ptp-per-session">per session</div>
                            </div>
                        </div>
                    </label>

                    <label class="ptp-package-option">
                        <input type="radio" name="package" value="5pack" <?php echo $package_type === '5pack' ? 'checked' : ''; ?>>
                        <div class="ptp-package-content">
                            <div class="ptp-package-radio"></div>
                            <div class="ptp-package-info">
                                <h4>5-Session Pack</h4>
                                <p>Great for building skills</p>
                            </div>
                            <div class="ptp-package-price">
                                <div class="ptp-price">$<?php echo number_format($pack_5_price); ?></div>
                                <div class="ptp-per-session">$<?php echo number_format($pack_5_price / 5, 2); ?>/session</div>
                            </div>
                        </div>
                        <div class="ptp-package-badge">Save 10%</div>
                    </label>

                    <label class="ptp-package-option">
                        <input type="radio" name="package" value="10pack" <?php echo $package_type === '10pack' ? 'checked' : ''; ?>>
                        <div class="ptp-package-content">
                            <div class="ptp-package-radio"></div>
                            <div class="ptp-package-info">
                                <h4>10-Session Pack</h4>
                                <p>Best value for serious athletes</p>
                            </div>
                            <div class="ptp-package-price">
                                <div class="ptp-price">$<?php echo number_format($pack_10_price); ?></div>
                                <div class="ptp-per-session">$<?php echo number_format($pack_10_price / 10, 2); ?>/session</div>
                            </div>
                        </div>
                        <div class="ptp-package-badge ptp-badge-value">Save 20%</div>
                    </label>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="ptp-form-card">
                <div class="ptp-form-card-header">
                    <div class="ptp-form-icon">📱</div>
                    <div>
                        <h2>Contact information</h2>
                        <p>We'll send session reminders here</p>
                    </div>
                </div>

                <div class="ptp-form-row">
                    <div class="ptp-form-group">
                        <label>First name</label>
                        <input type="text" name="first_name" class="ptp-input" placeholder="First name" value="<?php echo esc_attr($current_user->first_name); ?>" required>
                    </div>
                    <div class="ptp-form-group">
                        <label>Last name</label>
                        <input type="text" name="last_name" class="ptp-input" placeholder="Last name" value="<?php echo esc_attr($current_user->last_name); ?>" required>
                    </div>
                </div>

                <div class="ptp-form-group">
                    <label>Email address</label>
                    <input type="email" name="email" class="ptp-input" placeholder="email@example.com" value="<?php echo esc_attr($current_user->user_email); ?>" required>
                </div>

                <div class="ptp-form-group">
                    <label>Phone number</label>
                    <input type="tel" name="phone" class="ptp-input" placeholder="(555) 123-4567" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'phone', true)); ?>" required>
                </div>

                <div class="ptp-form-group">
                    <label>Notes for coach <span class="ptp-optional">(optional)</span></label>
                    <textarea name="notes" class="ptp-input ptp-textarea" rows="3" placeholder="Any specific goals, injuries, or things the coach should know..."></textarea>
                </div>
            </div>

            <!-- Payment -->
            <div class="ptp-form-card">
                <div class="ptp-form-card-header">
                    <div class="ptp-form-icon">💳</div>
                    <div>
                        <h2>Payment method</h2>
                        <p>All transactions are secure and encrypted</p>
                    </div>
                </div>

                <div class="ptp-payment-methods">
                    <label class="ptp-payment-method">
                        <input type="radio" name="payment_method" value="card" checked>
                        <div class="ptp-payment-content">
                            <span class="ptp-payment-icon">💳</span>
                            <span class="ptp-payment-label">Card</span>
                        </div>
                    </label>
                    <label class="ptp-payment-method">
                        <input type="radio" name="payment_method" value="apple_pay">
                        <div class="ptp-payment-content">
                            <span class="ptp-payment-icon">🍎</span>
                            <span class="ptp-payment-label">Apple Pay</span>
                        </div>
                    </label>
                    <label class="ptp-payment-method">
                        <input type="radio" name="payment_method" value="google_pay">
                        <div class="ptp-payment-content">
                            <span class="ptp-payment-icon">G</span>
                            <span class="ptp-payment-label">Google Pay</span>
                        </div>
                    </label>
                </div>

                <!-- Stripe Card Element -->
                <div id="cardElementContainer">
                    <div class="ptp-form-group">
                        <label>Card number</label>
                        <div id="card-number" class="ptp-stripe-element"></div>
                    </div>
                    <div class="ptp-card-row">
                        <div class="ptp-form-group">
                            <label>Expiration</label>
                            <div id="card-expiry" class="ptp-stripe-element"></div>
                        </div>
                        <div class="ptp-form-group">
                            <label>CVC</label>
                            <div id="card-cvc" class="ptp-stripe-element"></div>
                        </div>
                    </div>
                </div>

                <div id="card-errors" class="ptp-card-errors" role="alert"></div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="ptp-order-summary">
            <div class="ptp-summary-card">
                <div class="ptp-summary-header">
                    <h3>Order Summary</h3>
                    <p>Private Training Sessions</p>
                </div>

                <?php if ($trainer): ?>
                <div class="ptp-summary-coach">
                    <img src="<?php echo esc_url($trainer->profile_photo ?: 'https://ptpsoccercamps.com/wp-content/uploads/2024/01/default-coach.jpg'); ?>" alt="">
                    <div class="ptp-summary-coach-info">
                        <h4>Coach <?php echo esc_html($trainer->display_name); ?></h4>
                        <p>
                            <span class="ptp-stars">★</span> <?php echo round($avg_rating, 1); ?>
                            (<?php echo $review_count; ?> reviews)
                        </p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="ptp-summary-details">
                    <?php if ($selected_date): ?>
                    <div class="ptp-summary-detail">
                        <div class="ptp-detail-icon">📅</div>
                        <div class="ptp-detail-info">
                            <div class="ptp-detail-label">First Session</div>
                            <div class="ptp-detail-value"><?php echo date('D, M j', strtotime($selected_date)); ?> · <?php echo $selected_time ? date('g:i A', strtotime($selected_time)) : 'TBD'; ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($location): ?>
                    <div class="ptp-summary-detail">
                        <div class="ptp-detail-icon">📍</div>
                        <div class="ptp-detail-info">
                            <div class="ptp-detail-label">Location</div>
                            <div class="ptp-detail-value"><?php echo esc_html($location->name); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="ptp-summary-detail">
                        <div class="ptp-detail-icon">📦</div>
                        <div class="ptp-detail-info">
                            <div class="ptp-detail-label">Package</div>
                            <div class="ptp-detail-value" id="summaryPackage"><?php echo $package_name; ?></div>
                        </div>
                    </div>
                </div>

                <!-- Promo Code -->
                <div class="ptp-promo-section">
                    <button type="button" class="ptp-promo-toggle" id="promoToggle">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                            <line x1="7" y1="7" x2="7.01" y2="7"/>
                        </svg>
                        Have a promo code?
                    </button>
                    <div class="ptp-promo-form" id="promoForm" style="display: none;">
                        <input type="text" name="promo_code" class="ptp-input" placeholder="Enter code">
                        <button type="button" class="ptp-promo-apply" id="applyPromo">Apply</button>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="ptp-summary-pricing">
                    <div class="ptp-pricing-row">
                        <span class="ptp-pricing-label" id="pricingPackageLabel"><?php echo $package_name; ?></span>
                        <span class="ptp-pricing-value" id="pricingOriginal">$<?php echo number_format($original_price, 2); ?></span>
                    </div>
                    <?php if ($discount_amount > 0): ?>
                    <div class="ptp-pricing-row ptp-pricing-discount" id="discountRow">
                        <span class="ptp-pricing-label"><?php echo $discount_pct; ?>% Pack Discount</span>
                        <span class="ptp-pricing-value">-$<?php echo number_format($discount_amount, 2); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="ptp-pricing-row">
                        <span class="ptp-pricing-label">Booking fee</span>
                        <span class="ptp-pricing-value">$0.00</span>
                    </div>
                    <div class="ptp-pricing-row ptp-pricing-total">
                        <span class="ptp-pricing-label">Total</span>
                        <span class="ptp-pricing-value" id="pricingTotal">$<?php echo number_format($total_price, 2); ?></span>
                    </div>
                </div>

                <!-- CTA -->
                <div class="ptp-summary-cta">
                    <button type="submit" class="ptp-btn-book" id="submitBtn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        Complete Booking · <span id="btnTotal">$<?php echo number_format($total_price, 2); ?></span>
                    </button>
                    <div class="ptp-guarantee">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        Free cancellation up to 24 hours before
                    </div>
                </div>

                <!-- Trust Badges -->
                <div class="ptp-trust-badges">
                    <div class="ptp-trust-badge">
                        <div class="ptp-badge-icon">🔒</div>
                        <span>SSL Secure</span>
                    </div>
                    <div class="ptp-trust-badge">
                        <div class="ptp-badge-icon">💳</div>
                        <span>Stripe</span>
                    </div>
                    <div class="ptp-trust-badge">
                        <div class="ptp-badge-icon">✓</div>
                        <span>Verified</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</div>

<!-- Stripe JS -->
<script src="https://js.stripe.com/v3/"></script>
<script>
window.PTP_Checkout = {
    trainerId: <?php echo $trainer_id ?: 0; ?>,
    stripeKey: '<?php echo esc_js($stripe_pk); ?>',
    ajaxUrl: '<?php echo admin_url('admin-ajax.php'); ?>',
    nonce: '<?php echo wp_create_nonce('ptp_checkout_nonce'); ?>',
    pricing: {
        single: <?php echo $session_price; ?>,
        pack5: <?php echo $pack_5_price; ?>,
        pack10: <?php echo $pack_10_price; ?>
    },
    successUrl: '<?php echo home_url('/my-training/?booking=success'); ?>'
};
</script>
