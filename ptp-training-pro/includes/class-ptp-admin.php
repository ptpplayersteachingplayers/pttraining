<?php
/**
 * Admin Dashboard & Settings - v4.1
 * Enhanced admin pages for PTP Private Training
 */

if (!defined('ABSPATH')) exit;

class PTP_Admin {

    private static $instance = null;

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menus'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_ajax_ptp_update_session_status', array($this, 'ajax_update_session_status'));
        add_action('wp_ajax_ptp_approve_trainer', array($this, 'ajax_approve_trainer'));
        add_action('wp_ajax_ptp_reject_trainer', array($this, 'ajax_reject_trainer'));
        add_action('wp_ajax_ptp_process_payout', array($this, 'ajax_process_payout'));
    }

    /**
     * Add admin menus
     */
    public function add_admin_menus() {
        // Main menu
        add_menu_page(
            'PTP Private Training',
            'PTP Training',
            'manage_options',
            'ptp-training',
            array($this, 'render_dashboard_page'),
            'dashicons-groups',
            30
        );

        // Dashboard (same as main)
        add_submenu_page(
            'ptp-training',
            'Dashboard',
            'Dashboard',
            'manage_options',
            'ptp-training',
            array($this, 'render_dashboard_page')
        );

        // Sessions
        add_submenu_page(
            'ptp-training',
            'Sessions',
            'Sessions',
            'manage_options',
            'ptp-training-sessions',
            array($this, 'render_sessions_page')
        );

        // Trainers
        add_submenu_page(
            'ptp-training',
            'Trainers',
            'Trainers',
            'manage_options',
            'ptp-training-trainers',
            array($this, 'render_trainers_page')
        );

        // Applications
        add_submenu_page(
            'ptp-training',
            'Applications',
            'Applications',
            'manage_options',
            'ptp-training-applications',
            array($this, 'render_applications_page')
        );

        // Payouts
        add_submenu_page(
            'ptp-training',
            'Payouts',
            'Payouts',
            'manage_options',
            'ptp-training-payouts',
            array($this, 'render_payouts_page')
        );

        // Settings
        add_submenu_page(
            'ptp-training',
            'Settings',
            'Settings',
            'manage_options',
            'ptp-training-settings',
            array($this, 'render_settings_page')
        );
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        // Only load on PTP admin pages
        if (strpos($hook, 'ptp-training') === false) {
            return;
        }

        // Enqueue Google Fonts
        wp_enqueue_style(
            'ptp-google-fonts',
            'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
            array(),
            null
        );

        wp_enqueue_style(
            'ptp-admin-css',
            PTP_TRAINING_URL . 'assets/css/admin.css',
            array('ptp-google-fonts'),
            PTP_TRAINING_VERSION
        );

        wp_enqueue_script(
            'ptp-admin-js',
            PTP_TRAINING_URL . 'assets/js/admin.js',
            array('jquery'),
            PTP_TRAINING_VERSION,
            true
        );

        wp_localize_script('ptp-admin-js', 'ptpAdmin', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ptp_admin_nonce')
        ));
    }

    /**
     * Register settings
     */
    public function register_settings() {
        // Platform Settings
        register_setting('ptp_training_settings', 'ptp_platform_fee_percent');
        register_setting('ptp_training_settings', 'ptp_admin_email');
        register_setting('ptp_training_settings', 'ptp_auto_confirm_on_payment');
        register_setting('ptp_training_settings', 'ptp_default_session_duration');
        register_setting('ptp_training_settings', 'ptp_cancellation_window');
        register_setting('ptp_training_settings', 'ptp_require_payment_upfront');
        register_setting('ptp_training_settings', 'ptp_allow_guest_booking');

        // Page Settings
        register_setting('ptp_training_settings', 'ptp_page_marketplace');
        register_setting('ptp_training_settings', 'ptp_page_private_training');
        register_setting('ptp_training_settings', 'ptp_page_checkout');
        register_setting('ptp_training_settings', 'ptp_page_parent_dashboard');
        register_setting('ptp_training_settings', 'ptp_page_trainer_dashboard');
        register_setting('ptp_training_settings', 'ptp_page_application');
        register_setting('ptp_training_settings', 'ptp_trainer_slug');

        // Stripe Settings
        register_setting('ptp_training_settings', 'ptp_stripe_mode');
        register_setting('ptp_training_settings', 'ptp_stripe_publishable_key');
        register_setting('ptp_training_settings', 'ptp_stripe_secret_key');
        register_setting('ptp_training_settings', 'ptp_stripe_webhook_secret');
        register_setting('ptp_training_settings', 'ptp_stripe_connect_enabled');
        register_setting('ptp_training_settings', 'ptp_stripe_payout_delay');

        // Google Settings
        register_setting('ptp_training_settings', 'ptp_google_maps_key');
        register_setting('ptp_training_settings', 'ptp_google_client_id');
        register_setting('ptp_training_settings', 'ptp_google_client_secret');

        // SMS/Twilio Settings
        register_setting('ptp_training_settings', 'ptp_twilio_sid');
        register_setting('ptp_training_settings', 'ptp_twilio_token');
        register_setting('ptp_training_settings', 'ptp_twilio_phone');
        register_setting('ptp_training_settings', 'ptp_sms_enabled');
        register_setting('ptp_training_settings', 'ptp_sms_booking_confirmed');
        register_setting('ptp_training_settings', 'ptp_sms_reminder_24h');
        register_setting('ptp_training_settings', 'ptp_sms_reminder_1h');
        register_setting('ptp_training_settings', 'ptp_sms_cancellation');

        // HubSpot Settings
        register_setting('ptp_training_settings', 'ptp_hubspot_enabled');
        register_setting('ptp_training_settings', 'ptp_hubspot_api_key');

        // Notification Settings
        register_setting('ptp_training_settings', 'ptp_notification_admin_email');
        register_setting('ptp_training_settings', 'ptp_notification_from_name');
        register_setting('ptp_training_settings', 'ptp_notification_from_email');
        register_setting('ptp_training_settings', 'ptp_notification_reply_to');
        register_setting('ptp_training_settings', 'ptp_notify_parent_booking_request');
        register_setting('ptp_training_settings', 'ptp_notify_trainer_booking_request');
        register_setting('ptp_training_settings', 'ptp_notify_admin_booking_request');
        register_setting('ptp_training_settings', 'ptp_notify_parent_session_confirmed');
        register_setting('ptp_training_settings', 'ptp_notify_trainer_session_confirmed');
        register_setting('ptp_training_settings', 'ptp_notify_parent_session_cancelled');
        register_setting('ptp_training_settings', 'ptp_notify_trainer_session_cancelled');
        register_setting('ptp_training_settings', 'ptp_notify_parent_session_completed');
        register_setting('ptp_training_settings', 'ptp_notify_parent_payment_success');
        register_setting('ptp_training_settings', 'ptp_notify_admin_payment_success');

        // Email Templates
        register_setting('ptp_training_settings', 'ptp_email_booking_request_subject');
        register_setting('ptp_training_settings', 'ptp_email_booking_request_intro');
        register_setting('ptp_training_settings', 'ptp_email_session_confirmed_subject');
        register_setting('ptp_training_settings', 'ptp_email_session_confirmed_intro');

        // Branding Settings
        register_setting('ptp_training_settings', 'ptp_brand_primary_color');
        register_setting('ptp_training_settings', 'ptp_brand_secondary_color');
        register_setting('ptp_training_settings', 'ptp_social_instagram');
        register_setting('ptp_training_settings', 'ptp_social_facebook');
        register_setting('ptp_training_settings', 'ptp_social_twitter');
        register_setting('ptp_training_settings', 'ptp_social_youtube');
        register_setting('ptp_training_settings', 'ptp_social_tiktok');

        // Advanced Settings
        register_setting('ptp_training_settings', 'ptp_debug_mode');
        register_setting('ptp_training_settings', 'ptp_disable_css');
    }

    /**
     * Render Dashboard Page
     */
    public function render_dashboard_page() {
        $stats = PTP_Database::get_admin_stats();
        ?>
        <div class="wrap ptp-admin">
            <h1 class="ptp-admin-title">
                <span class="ptp-admin-logo">PTP</span>
                Private Training Dashboard
            </h1>

            <!-- Stats Grid -->
            <div class="ptp-stats-grid">
                <div class="ptp-stat-card">
                    <div class="ptp-stat-icon ptp-stat-icon--trainers">
                        <span class="dashicons dashicons-groups"></span>
                    </div>
                    <div class="ptp-stat-content">
                        <div class="ptp-stat-value"><?php echo number_format($stats['total_trainers']); ?></div>
                        <div class="ptp-stat-label">Active Trainers</div>
                    </div>
                </div>

                <div class="ptp-stat-card ptp-stat-card--highlight">
                    <div class="ptp-stat-icon ptp-stat-icon--pending">
                        <span class="dashicons dashicons-clock"></span>
                    </div>
                    <div class="ptp-stat-content">
                        <div class="ptp-stat-value"><?php echo number_format($stats['pending_applications']); ?></div>
                        <div class="ptp-stat-label">Pending Applications</div>
                    </div>
                    <?php if ($stats['pending_applications'] > 0): ?>
                        <a href="<?php echo admin_url('admin.php?page=ptp-training-applications'); ?>" class="ptp-stat-action">Review Now</a>
                    <?php endif; ?>
                </div>

                <div class="ptp-stat-card">
                    <div class="ptp-stat-icon ptp-stat-icon--sessions">
                        <span class="dashicons dashicons-calendar-alt"></span>
                    </div>
                    <div class="ptp-stat-content">
                        <div class="ptp-stat-value"><?php echo number_format($stats['upcoming_sessions']); ?></div>
                        <div class="ptp-stat-label">Upcoming Sessions</div>
                    </div>
                </div>

                <div class="ptp-stat-card">
                    <div class="ptp-stat-icon ptp-stat-icon--completed">
                        <span class="dashicons dashicons-yes-alt"></span>
                    </div>
                    <div class="ptp-stat-content">
                        <div class="ptp-stat-value"><?php echo number_format($stats['completed_sessions']); ?></div>
                        <div class="ptp-stat-label">Completed Sessions</div>
                    </div>
                </div>

                <div class="ptp-stat-card ptp-stat-card--money">
                    <div class="ptp-stat-icon ptp-stat-icon--revenue">
                        <span class="dashicons dashicons-chart-bar"></span>
                    </div>
                    <div class="ptp-stat-content">
                        <div class="ptp-stat-value">$<?php echo number_format($stats['total_revenue'], 0); ?></div>
                        <div class="ptp-stat-label">Total Revenue</div>
                    </div>
                </div>

                <div class="ptp-stat-card ptp-stat-card--money">
                    <div class="ptp-stat-icon ptp-stat-icon--platform">
                        <span class="dashicons dashicons-money-alt"></span>
                    </div>
                    <div class="ptp-stat-content">
                        <div class="ptp-stat-value">$<?php echo number_format($stats['platform_revenue'], 0); ?></div>
                        <div class="ptp-stat-label">Platform Revenue</div>
                    </div>
                </div>

                <?php if ($stats['requested_sessions'] > 0): ?>
                <div class="ptp-stat-card ptp-stat-card--alert">
                    <div class="ptp-stat-icon ptp-stat-icon--requested">
                        <span class="dashicons dashicons-warning"></span>
                    </div>
                    <div class="ptp-stat-content">
                        <div class="ptp-stat-value"><?php echo number_format($stats['requested_sessions']); ?></div>
                        <div class="ptp-stat-label">Pending Session Requests</div>
                    </div>
                    <a href="<?php echo admin_url('admin.php?page=ptp-training-sessions&status=requested'); ?>" class="ptp-stat-action">Review</a>
                </div>
                <?php endif; ?>

                <?php if ($stats['pending_payouts'] > 0): ?>
                <div class="ptp-stat-card">
                    <div class="ptp-stat-icon ptp-stat-icon--payouts">
                        <span class="dashicons dashicons-update"></span>
                    </div>
                    <div class="ptp-stat-content">
                        <div class="ptp-stat-value"><?php echo number_format($stats['pending_payouts']); ?></div>
                        <div class="ptp-stat-label">Pending Payouts ($<?php echo number_format($stats['pending_payout_amount'], 0); ?>)</div>
                    </div>
                    <a href="<?php echo admin_url('admin.php?page=ptp-training-payouts'); ?>" class="ptp-stat-action">Process</a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Quick Actions -->
            <div class="ptp-admin-section">
                <h2>Quick Actions</h2>
                <div class="ptp-quick-actions">
                    <a href="<?php echo admin_url('admin.php?page=ptp-training-sessions'); ?>" class="ptp-quick-action">
                        <span class="dashicons dashicons-calendar-alt"></span>
                        View All Sessions
                    </a>
                    <a href="<?php echo admin_url('admin.php?page=ptp-training-trainers'); ?>" class="ptp-quick-action">
                        <span class="dashicons dashicons-groups"></span>
                        Manage Trainers
                    </a>
                    <a href="<?php echo admin_url('admin.php?page=ptp-training-applications'); ?>" class="ptp-quick-action">
                        <span class="dashicons dashicons-welcome-write-blog"></span>
                        Review Applications
                    </a>
                    <a href="<?php echo admin_url('admin.php?page=ptp-training-settings'); ?>" class="ptp-quick-action">
                        <span class="dashicons dashicons-admin-settings"></span>
                        Plugin Settings
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="ptp-admin-section">
                <h2>Recent Sessions</h2>
                <?php
                $recent_sessions = PTP_Database::get_sessions(array('limit' => 10));
                if ($recent_sessions):
                ?>
                <table class="ptp-admin-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Trainer</th>
                            <th>Player</th>
                            <th>Date/Time</th>
                            <th>Price</th>
                            <th>Session Status</th>
                            <th>Payment Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_sessions as $session): ?>
                        <tr>
                            <td>
                                <strong><?php echo esc_html($session->customer_name); ?></strong><br>
                                <small><?php echo esc_html($session->customer_email); ?></small>
                            </td>
                            <td><?php echo esc_html($session->trainer_name); ?></td>
                            <td>
                                <?php echo esc_html($session->player_name ?: 'N/A'); ?>
                                <?php if ($session->player_age): ?>
                                    <br><small>Age <?php echo esc_html($session->player_age); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($session->session_date !== '0000-00-00'): ?>
                                    <?php echo date('M j, Y', strtotime($session->session_date)); ?><br>
                                    <small><?php echo date('g:i A', strtotime($session->start_time)); ?></small>
                                <?php else: ?>
                                    <em>Not scheduled</em>
                                <?php endif; ?>
                            </td>
                            <td>$<?php echo number_format($session->price, 2); ?></td>
                            <td>
                                <span class="ptp-status ptp-status--<?php echo esc_attr($session->session_status); ?>">
                                    <?php echo esc_html(ucfirst($session->session_status)); ?>
                                </span>
                            </td>
                            <td>
                                <span class="ptp-status ptp-status--payment-<?php echo esc_attr($session->payment_status); ?>">
                                    <?php echo esc_html(ucfirst($session->payment_status)); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="ptp-empty">No sessions yet.</p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Render Sessions Page
     */
    public function render_sessions_page() {
        // Get filters
        $status_filter = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';
        $payment_filter = isset($_GET['payment']) ? sanitize_text_field($_GET['payment']) : '';
        $trainer_filter = isset($_GET['trainer']) ? intval($_GET['trainer']) : 0;
        $date_from = isset($_GET['date_from']) ? sanitize_text_field($_GET['date_from']) : '';
        $date_to = isset($_GET['date_to']) ? sanitize_text_field($_GET['date_to']) : '';

        $args = array(
            'session_status' => $status_filter,
            'payment_status' => $payment_filter,
            'trainer_id' => $trainer_filter,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'limit' => 50
        );

        $sessions = PTP_Database::get_sessions($args);
        $trainers = PTP_Database::get_trainers(array('status' => 'approved', 'limit' => 100));
        ?>
        <div class="wrap ptp-admin">
            <h1 class="ptp-admin-title">Sessions</h1>

            <!-- Filters -->
            <div class="ptp-filters">
                <form method="get" class="ptp-filter-form">
                    <input type="hidden" name="page" value="ptp-training-sessions">

                    <div class="ptp-filter-group">
                        <label>Session Status</label>
                        <select name="status">
                            <option value="">All Statuses</option>
                            <option value="requested" <?php selected($status_filter, 'requested'); ?>>Requested</option>
                            <option value="confirmed" <?php selected($status_filter, 'confirmed'); ?>>Confirmed</option>
                            <option value="scheduled" <?php selected($status_filter, 'scheduled'); ?>>Scheduled</option>
                            <option value="completed" <?php selected($status_filter, 'completed'); ?>>Completed</option>
                            <option value="cancelled" <?php selected($status_filter, 'cancelled'); ?>>Cancelled</option>
                            <option value="no_show" <?php selected($status_filter, 'no_show'); ?>>No Show</option>
                        </select>
                    </div>

                    <div class="ptp-filter-group">
                        <label>Payment Status</label>
                        <select name="payment">
                            <option value="">All Payments</option>
                            <option value="unpaid" <?php selected($payment_filter, 'unpaid'); ?>>Unpaid</option>
                            <option value="pending" <?php selected($payment_filter, 'pending'); ?>>Pending</option>
                            <option value="paid" <?php selected($payment_filter, 'paid'); ?>>Paid</option>
                            <option value="refunded" <?php selected($payment_filter, 'refunded'); ?>>Refunded</option>
                        </select>
                    </div>

                    <div class="ptp-filter-group">
                        <label>Trainer</label>
                        <select name="trainer">
                            <option value="">All Trainers</option>
                            <?php foreach ($trainers as $trainer): ?>
                                <option value="<?php echo $trainer->id; ?>" <?php selected($trainer_filter, $trainer->id); ?>>
                                    <?php echo esc_html($trainer->display_name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="ptp-filter-group">
                        <label>Date From</label>
                        <input type="date" name="date_from" value="<?php echo esc_attr($date_from); ?>">
                    </div>

                    <div class="ptp-filter-group">
                        <label>Date To</label>
                        <input type="date" name="date_to" value="<?php echo esc_attr($date_to); ?>">
                    </div>

                    <button type="submit" class="button">Filter</button>
                    <a href="<?php echo admin_url('admin.php?page=ptp-training-sessions'); ?>" class="button">Reset</a>
                </form>
            </div>

            <!-- Sessions Table -->
            <?php if ($sessions): ?>
            <table class="ptp-admin-table widefat">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Parent/Customer</th>
                        <th>Trainer</th>
                        <th>Player</th>
                        <th>Date/Time</th>
                        <th>Price</th>
                        <th>Session Status</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sessions as $session): ?>
                    <tr data-session-id="<?php echo $session->id; ?>">
                        <td>#<?php echo $session->id; ?></td>
                        <td>
                            <strong><?php echo esc_html($session->customer_name); ?></strong><br>
                            <small><?php echo esc_html($session->customer_email); ?></small>
                        </td>
                        <td><?php echo esc_html($session->trainer_name); ?></td>
                        <td>
                            <?php echo esc_html($session->player_name ?: 'N/A'); ?>
                            <?php if ($session->player_age): ?>
                                <br><small>Age <?php echo esc_html($session->player_age); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($session->session_date !== '0000-00-00'): ?>
                                <?php echo date('M j, Y', strtotime($session->session_date)); ?><br>
                                <small><?php echo date('g:i A', strtotime($session->start_time)); ?></small>
                            <?php else: ?>
                                <em>Not scheduled</em>
                            <?php endif; ?>
                        </td>
                        <td>$<?php echo number_format($session->price, 2); ?></td>
                        <td>
                            <select class="ptp-session-status-select" data-session-id="<?php echo $session->id; ?>">
                                <option value="requested" <?php selected($session->session_status, 'requested'); ?>>Requested</option>
                                <option value="confirmed" <?php selected($session->session_status, 'confirmed'); ?>>Confirmed</option>
                                <option value="scheduled" <?php selected($session->session_status, 'scheduled'); ?>>Scheduled</option>
                                <option value="completed" <?php selected($session->session_status, 'completed'); ?>>Completed</option>
                                <option value="cancelled" <?php selected($session->session_status, 'cancelled'); ?>>Cancelled</option>
                                <option value="no_show" <?php selected($session->session_status, 'no_show'); ?>>No Show</option>
                            </select>
                        </td>
                        <td>
                            <span class="ptp-status ptp-status--payment-<?php echo esc_attr($session->payment_status); ?>">
                                <?php echo esc_html(ucfirst($session->payment_status)); ?>
                            </span>
                        </td>
                        <td>
                            <a href="#" class="ptp-view-session" data-session-id="<?php echo $session->id; ?>">View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p class="ptp-empty">No sessions found matching your criteria.</p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render Trainers Page
     */
    public function render_trainers_page() {
        $trainers = PTP_Database::get_trainers(array('status' => 'approved', 'limit' => 100));
        ?>
        <div class="wrap ptp-admin">
            <h1 class="ptp-admin-title">Trainers</h1>

            <?php if ($trainers): ?>
            <table class="ptp-admin-table widefat">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Rate</th>
                        <th>Rating</th>
                        <th>Sessions</th>
                        <th>Stripe</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($trainers as $trainer): ?>
                    <tr>
                        <td>
                            <?php if ($trainer->profile_photo): ?>
                                <img src="<?php echo esc_url($trainer->profile_photo); ?>" alt="" class="ptp-trainer-thumb">
                            <?php else: ?>
                                <div class="ptp-trainer-thumb ptp-trainer-thumb--placeholder">
                                    <?php echo esc_html(strtoupper(substr($trainer->display_name, 0, 1))); ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo esc_html($trainer->display_name); ?></strong><br>
                            <small><?php echo esc_html($trainer->tagline ?: ''); ?></small>
                        </td>
                        <td>
                            <?php echo esc_html($trainer->primary_location_city . ', ' . $trainer->primary_location_state); ?>
                        </td>
                        <td>$<?php echo number_format($trainer->hourly_rate, 0); ?>/hr</td>
                        <td>
                            <?php if ($trainer->avg_rating > 0): ?>
                                <span class="ptp-rating">
                                    <?php echo number_format($trainer->avg_rating, 1); ?>
                                    <small>(<?php echo $trainer->total_reviews; ?>)</small>
                                </span>
                            <?php else: ?>
                                <em>No reviews</em>
                            <?php endif; ?>
                        </td>
                        <td><?php echo number_format($trainer->total_sessions); ?></td>
                        <td>
                            <?php if ($trainer->stripe_onboarding_complete): ?>
                                <span class="ptp-status ptp-status--confirmed">Connected</span>
                            <?php elseif ($trainer->stripe_account_id): ?>
                                <span class="ptp-status ptp-status--pending">Pending</span>
                            <?php else: ?>
                                <span class="ptp-status ptp-status--unpaid">Not Set</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($trainer->is_featured): ?>
                                <span class="ptp-badge ptp-badge--featured">Featured</span>
                            <?php else: ?>
                                <button class="button button-small ptp-feature-trainer" data-trainer-id="<?php echo $trainer->id; ?>">Feature</button>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo home_url('/trainer/' . $trainer->slug . '/'); ?>" target="_blank">View</a> |
                            <a href="<?php echo admin_url('admin.php?page=ptp-training-sessions&trainer=' . $trainer->id); ?>">Sessions</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p class="ptp-empty">No approved trainers yet.</p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render Applications Page
     */
    public function render_applications_page() {
        global $wpdb;
        $applications = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}ptp_applications ORDER BY status = 'pending' DESC, created_at DESC LIMIT 50"
        );
        ?>
        <div class="wrap ptp-admin">
            <h1 class="ptp-admin-title">Trainer Applications</h1>

            <?php if ($applications): ?>
            <div class="ptp-applications-grid">
                <?php foreach ($applications as $app): ?>
                <div class="ptp-application-card ptp-application-card--<?php echo esc_attr($app->status); ?>">
                    <div class="ptp-application-header">
                        <h3><?php echo esc_html($app->first_name . ' ' . $app->last_name); ?></h3>
                        <span class="ptp-status ptp-status--<?php echo esc_attr($app->status); ?>">
                            <?php echo esc_html(ucfirst($app->status)); ?>
                        </span>
                    </div>
                    <div class="ptp-application-body">
                        <p><strong>Email:</strong> <?php echo esc_html($app->email); ?></p>
                        <p><strong>Phone:</strong> <?php echo esc_html($app->phone); ?></p>
                        <p><strong>Location:</strong> <?php echo esc_html($app->location_city . ', ' . $app->location_state); ?></p>
                        <?php if ($app->experience_summary): ?>
                        <p><strong>Experience:</strong><br><?php echo esc_html(wp_trim_words($app->experience_summary, 30)); ?></p>
                        <?php endif; ?>
                        <?php if ($app->intro_video_url): ?>
                        <p><a href="<?php echo esc_url($app->intro_video_url); ?>" target="_blank">View Intro Video</a></p>
                        <?php endif; ?>
                        <p class="ptp-application-date">Applied <?php echo date('M j, Y', strtotime($app->created_at)); ?></p>
                    </div>
                    <?php if ($app->status === 'pending'): ?>
                    <div class="ptp-application-actions">
                        <button class="button button-primary ptp-approve-application" data-app-id="<?php echo $app->id; ?>">
                            Approve
                        </button>
                        <button class="button ptp-reject-application" data-app-id="<?php echo $app->id; ?>">
                            Reject
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="ptp-empty">No applications yet.</p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render Payouts Page
     */
    public function render_payouts_page() {
        global $wpdb;
        $payouts = $wpdb->get_results(
            "SELECT p.*, t.display_name as trainer_name
             FROM {$wpdb->prefix}ptp_payouts p
             LEFT JOIN {$wpdb->prefix}ptp_trainers t ON p.trainer_id = t.id
             ORDER BY p.status = 'pending' DESC, p.created_at DESC
             LIMIT 100"
        );
        ?>
        <div class="wrap ptp-admin">
            <h1 class="ptp-admin-title">Trainer Payouts</h1>

            <?php if ($payouts): ?>
            <table class="ptp-admin-table widefat">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Trainer</th>
                        <th>Gross</th>
                        <th>Platform Fee</th>
                        <th>Trainer Payout</th>
                        <th>Status</th>
                        <th>Paid At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payouts as $payout): ?>
                    <tr>
                        <td>#<?php echo $payout->id; ?></td>
                        <td><?php echo esc_html($payout->trainer_name); ?></td>
                        <td>$<?php echo number_format($payout->gross_amount, 2); ?></td>
                        <td>$<?php echo number_format($payout->platform_fee, 2); ?></td>
                        <td><strong>$<?php echo number_format($payout->trainer_payout, 2); ?></strong></td>
                        <td>
                            <span class="ptp-status ptp-status--<?php echo $payout->status === 'paid' ? 'confirmed' : 'pending'; ?>">
                                <?php echo esc_html(ucfirst($payout->status)); ?>
                            </span>
                        </td>
                        <td>
                            <?php echo $payout->paid_at ? date('M j, Y', strtotime($payout->paid_at)) : '-'; ?>
                        </td>
                        <td>
                            <?php if ($payout->status === 'pending'): ?>
                                <button class="button button-primary button-small ptp-process-payout" data-payout-id="<?php echo $payout->id; ?>">
                                    Process
                                </button>
                            <?php elseif ($payout->stripe_transfer_id): ?>
                                <small><?php echo esc_html($payout->stripe_transfer_id); ?></small>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p class="ptp-empty">No payouts yet.</p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render Settings Page - Robust Version with Inline Styles
     */
    public function render_settings_page() {
        $current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';
        $tabs = array(
            'general' => array('label' => 'General', 'icon' => '⚙️'),
            'pages' => array('label' => 'Pages & URLs', 'icon' => '📄'),
            'stripe' => array('label' => 'Payments', 'icon' => '💳'),
            'notifications' => array('label' => 'Notifications', 'icon' => '📧'),
            'sms' => array('label' => 'SMS', 'icon' => '📱'),
            'integrations' => array('label' => 'Integrations', 'icon' => '🔌'),
            'branding' => array('label' => 'Branding', 'icon' => '🎨'),
            'advanced' => array('label' => 'Advanced', 'icon' => '🔧'),
        );
        ?>
        <style>
            .ptp-settings-wrap { max-width: 1200px; margin: 20px 20px 20px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
            .ptp-settings-wrap * { box-sizing: border-box; }
            .ptp-settings-header { margin-bottom: 24px; }
            .ptp-settings-header h1 { display: flex; align-items: center; gap: 12px; font-size: 28px; font-weight: 700; margin: 0 0 8px; }
            .ptp-settings-header h1 .ptp-badge { background: linear-gradient(135deg, #FCB900, #E5A800); color: #0E0F11; padding: 6px 14px; border-radius: 6px; font-size: 14px; font-weight: 800; }
            .ptp-settings-header p { color: #6B7280; font-size: 15px; margin: 0; }
            
            .ptp-settings-layout { display: grid; grid-template-columns: 200px 1fr; gap: 24px; }
            @media (max-width: 900px) { .ptp-settings-layout { grid-template-columns: 1fr; } }
            
            .ptp-settings-nav { background: #fff; border-radius: 12px; padding: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); height: fit-content; position: sticky; top: 32px; }
            .ptp-settings-nav a { display: flex; align-items: center; gap: 10px; padding: 12px 14px; border-radius: 8px; color: #4B5563; text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.15s; }
            .ptp-settings-nav a:hover { background: #F3F4F6; color: #111827; }
            .ptp-settings-nav a.active { background: #FEF3C7; color: #0E0F11; font-weight: 600; }
            .ptp-settings-nav a span.icon { font-size: 16px; }
            
            .ptp-settings-content { min-width: 0; }
            
            .ptp-card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px; overflow: hidden; }
            .ptp-card-header { padding: 20px 24px; border-bottom: 1px solid #F3F4F6; background: #FAFAFA; }
            .ptp-card-header h2 { font-size: 16px; font-weight: 700; margin: 0 0 4px; color: #111827; }
            .ptp-card-header p { font-size: 13px; color: #6B7280; margin: 0; }
            .ptp-card-body { padding: 24px; }
            
            .ptp-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
            @media (max-width: 700px) { .ptp-grid { grid-template-columns: 1fr; } }
            .ptp-grid .full { grid-column: 1 / -1; }
            
            .ptp-field { display: flex; flex-direction: column; gap: 6px; }
            .ptp-field label { font-size: 13px; font-weight: 600; color: #374151; }
            .ptp-field input[type="text"], .ptp-field input[type="email"], .ptp-field input[type="password"], 
            .ptp-field input[type="number"], .ptp-field input[type="url"], .ptp-field select, .ptp-field textarea {
                padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; width: 100%; transition: all 0.15s;
            }
            .ptp-field input:focus, .ptp-field select:focus, .ptp-field textarea:focus { outline: none; border-color: #FCB900; box-shadow: 0 0 0 3px rgba(252,185,0,0.2); }
            .ptp-field .hint { font-size: 12px; color: #9CA3AF; }
            .ptp-field .hint code { background: #F3F4F6; padding: 2px 6px; border-radius: 4px; font-size: 11px; }
            
            .ptp-toggle-row { display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid #F3F4F6; }
            .ptp-toggle-row:last-child { border-bottom: none; }
            .ptp-toggle-row input[type="checkbox"] { width: 44px; height: 24px; appearance: none; background: #D1D5DB; border-radius: 12px; position: relative; cursor: pointer; transition: all 0.2s; }
            .ptp-toggle-row input[type="checkbox"]::after { content: ''; width: 18px; height: 18px; background: #fff; border-radius: 50%; position: absolute; top: 3px; left: 3px; transition: all 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.2); }
            .ptp-toggle-row input[type="checkbox"]:checked { background: #FCB900; }
            .ptp-toggle-row input[type="checkbox"]:checked::after { transform: translateX(20px); }
            .ptp-toggle-row span { font-size: 14px; color: #374151; }
            
            .ptp-alert { display: flex; gap: 12px; padding: 14px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; }
            .ptp-alert-info { background: #DBEAFE; color: #1E40AF; }
            .ptp-alert a { color: inherit; font-weight: 600; }
            
            .ptp-input-group { display: flex; }
            .ptp-input-group .prefix { padding: 10px 12px; background: #F3F4F6; border: 1px solid #D1D5DB; border-right: none; border-radius: 8px 0 0 8px; font-size: 13px; color: #6B7280; white-space: nowrap; }
            .ptp-input-group input { border-radius: 0 8px 8px 0; flex: 1; }
            
            .ptp-radio-row { display: flex; gap: 24px; margin-bottom: 16px; }
            .ptp-radio-row label { display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; }
            .ptp-radio-row input[type="radio"] { width: 18px; height: 18px; accent-color: #FCB900; }
            
            .ptp-notify-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
            @media (max-width: 700px) { .ptp-notify-grid { grid-template-columns: 1fr; } }
            .ptp-notify-section h4 { font-size: 12px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 12px; padding-bottom: 8px; border-bottom: 1px solid #F3F4F6; }
            
            .ptp-system-table { width: 100%; font-size: 13px; }
            .ptp-system-table td { padding: 10px 0; border-bottom: 1px solid #F3F4F6; }
            .ptp-system-table td:first-child { font-weight: 600; color: #6B7280; width: 150px; }
            .ptp-system-table code { background: #F3F4F6; padding: 4px 8px; border-radius: 4px; font-size: 11px; }
            
            .ptp-settings-footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #E5E7EB; }
            .ptp-settings-footer .button-primary { background: #FCB900 !important; border-color: #FCB900 !important; color: #0E0F11 !important; padding: 10px 24px !important; height: auto !important; font-weight: 600 !important; }
            .ptp-settings-footer .button-primary:hover { background: #E5A800 !important; border-color: #E5A800 !important; }
        </style>
        
        <div class="ptp-settings-wrap">
            <div class="ptp-settings-header">
                <h1><span class="ptp-badge">PTP</span> Settings</h1>
                <p>Configure your private training platform</p>
            </div>

            <div class="ptp-settings-layout">
                <!-- Sidebar Navigation -->
                <nav class="ptp-settings-nav">
                    <?php foreach ($tabs as $tab_id => $tab): ?>
                    <a href="?page=ptp-training-settings&tab=<?php echo $tab_id; ?>" class="<?php echo $current_tab === $tab_id ? 'active' : ''; ?>">
                        <span class="icon"><?php echo $tab['icon']; ?></span>
                        <?php echo $tab['label']; ?>
                    </a>
                    <?php endforeach; ?>
                </nav>

                <!-- Settings Content -->
                <div class="ptp-settings-content">
                    <form method="post" action="options.php">
                        <?php settings_fields('ptp_training_settings'); ?>

                        <?php if ($current_tab === 'general'): ?>
                        <!-- GENERAL TAB -->
                        <div class="ptp-card">
                            <div class="ptp-card-header">
                                <h2>Platform Settings</h2>
                                <p>Core configuration for your training marketplace</p>
                            </div>
                            <div class="ptp-card-body">
                                <div class="ptp-grid">
                                    <div class="ptp-field">
                                        <label>Platform Fee (%)</label>
                                        <input type="number" name="ptp_platform_fee_percent" value="<?php echo esc_attr(get_option('ptp_platform_fee_percent', 20)); ?>" min="0" max="50" step="0.5">
                                        <span class="hint">Percentage kept from each transaction</span>
                                    </div>
                                    <div class="ptp-field">
                                        <label>Admin Email</label>
                                        <input type="email" name="ptp_admin_email" value="<?php echo esc_attr(get_option('ptp_admin_email', get_option('admin_email'))); ?>">
                                        <span class="hint">Primary email for notifications</span>
                                    </div>
                                    <div class="ptp-field">
                                        <label>Default Session Duration</label>
                                        <select name="ptp_default_session_duration">
                                            <option value="30" <?php selected(get_option('ptp_default_session_duration', 60), 30); ?>>30 minutes</option>
                                            <option value="45" <?php selected(get_option('ptp_default_session_duration', 60), 45); ?>>45 minutes</option>
                                            <option value="60" <?php selected(get_option('ptp_default_session_duration', 60), 60); ?>>1 hour</option>
                                            <option value="90" <?php selected(get_option('ptp_default_session_duration', 60), 90); ?>>1.5 hours</option>
                                            <option value="120" <?php selected(get_option('ptp_default_session_duration', 60), 120); ?>>2 hours</option>
                                        </select>
                                    </div>
                                    <div class="ptp-field">
                                        <label>Cancellation Window (hours)</label>
                                        <input type="number" name="ptp_cancellation_window" value="<?php echo esc_attr(get_option('ptp_cancellation_window', 24)); ?>" min="0" max="168">
                                        <span class="hint">Hours before session when free cancellation ends</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ptp-card">
                            <div class="ptp-card-header">
                                <h2>Booking Settings</h2>
                                <p>Control how bookings are processed</p>
                            </div>
                            <div class="ptp-card-body">
                                <div class="ptp-toggle-row">
                                    <input type="checkbox" name="ptp_auto_confirm_on_payment" value="1" <?php checked(get_option('ptp_auto_confirm_on_payment'), 1); ?>>
                                    <span>Auto-confirm sessions when payment succeeds</span>
                                </div>
                                <div class="ptp-toggle-row">
                                    <input type="checkbox" name="ptp_require_payment_upfront" value="1" <?php checked(get_option('ptp_require_payment_upfront'), 1); ?>>
                                    <span>Require payment before session is confirmed</span>
                                </div>
                                <div class="ptp-toggle-row">
                                    <input type="checkbox" name="ptp_allow_guest_booking" value="1" <?php checked(get_option('ptp_allow_guest_booking', 1), 1); ?>>
                                    <span>Allow booking without account (creates account automatically)</span>
                                </div>
                            </div>
                        </div>

                        <?php elseif ($current_tab === 'pages'): ?>
                        <!-- PAGES TAB -->
                        <div class="ptp-card">
                            <div class="ptp-card-header">
                                <h2>Page Configuration</h2>
                                <p>Assign pages for each part of your training platform</p>
                            </div>
                            <div class="ptp-card-body">
                                <?php $pages = get_pages(); ?>
                                <div class="ptp-grid">
                                    <div class="ptp-field">
                                        <label>Trainer Directory Page</label>
                                        <select name="ptp_page_marketplace">
                                            <option value="">— Select —</option>
                                            <?php foreach ($pages as $page): ?>
                                            <option value="<?php echo $page->ID; ?>" <?php selected(get_option('ptp_page_marketplace'), $page->ID); ?>><?php echo esc_html($page->post_title); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="hint">Shortcode: <code>[ptp_training_directory]</code></span>
                                    </div>
                                    <div class="ptp-field">
                                        <label>Private Training Landing</label>
                                        <select name="ptp_page_private_training">
                                            <option value="">— Select —</option>
                                            <?php foreach ($pages as $page): ?>
                                            <option value="<?php echo $page->ID; ?>" <?php selected(get_option('ptp_page_private_training'), $page->ID); ?>><?php echo esc_html($page->post_title); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="hint">Shortcode: <code>[ptp_private_training]</code></span>
                                    </div>
                                    <div class="ptp-field">
                                        <label>Checkout Page</label>
                                        <select name="ptp_page_checkout">
                                            <option value="">— Select —</option>
                                            <?php foreach ($pages as $page): ?>
                                            <option value="<?php echo $page->ID; ?>" <?php selected(get_option('ptp_page_checkout'), $page->ID); ?>><?php echo esc_html($page->post_title); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="hint">Shortcode: <code>[ptp_training_checkout]</code></span>
                                    </div>
                                    <div class="ptp-field">
                                        <label>Parent Dashboard</label>
                                        <select name="ptp_page_parent_dashboard">
                                            <option value="">— Select —</option>
                                            <?php foreach ($pages as $page): ?>
                                            <option value="<?php echo $page->ID; ?>" <?php selected(get_option('ptp_page_parent_dashboard'), $page->ID); ?>><?php echo esc_html($page->post_title); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="hint">Shortcode: <code>[ptp_parent_dashboard]</code></span>
                                    </div>
                                    <div class="ptp-field">
                                        <label>Trainer Dashboard</label>
                                        <select name="ptp_page_trainer_dashboard">
                                            <option value="">— Select —</option>
                                            <?php foreach ($pages as $page): ?>
                                            <option value="<?php echo $page->ID; ?>" <?php selected(get_option('ptp_page_trainer_dashboard'), $page->ID); ?>><?php echo esc_html($page->post_title); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="hint">Shortcode: <code>[ptp_trainer_dashboard]</code></span>
                                    </div>
                                    <div class="ptp-field">
                                        <label>Trainer Application</label>
                                        <select name="ptp_page_application">
                                            <option value="">— Select —</option>
                                            <?php foreach ($pages as $page): ?>
                                            <option value="<?php echo $page->ID; ?>" <?php selected(get_option('ptp_page_application'), $page->ID); ?>><?php echo esc_html($page->post_title); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="hint">Shortcode: <code>[ptp_application_form]</code></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ptp-card">
                            <div class="ptp-card-header">
                                <h2>URL Structure</h2>
                                <p>Configure trainer profile URLs</p>
                            </div>
                            <div class="ptp-card-body">
                                <div class="ptp-field">
                                    <label>Trainer Profile Base</label>
                                    <div class="ptp-input-group">
                                        <span class="prefix"><?php echo home_url('/'); ?></span>
                                        <input type="text" name="ptp_trainer_slug" value="<?php echo esc_attr(get_option('ptp_trainer_slug', 'trainer')); ?>">
                                    </div>
                                    <span class="hint">Flush permalinks after changing</span>
                                </div>
                            </div>
                        </div>

                        <?php elseif ($current_tab === 'stripe'): ?>
                        <!-- PAYMENTS TAB -->
                        <div class="ptp-card">
                            <div class="ptp-card-header">
                                <h2>Stripe Configuration</h2>
                                <p>Connect your Stripe account to accept payments</p>
                            </div>
                            <div class="ptp-card-body">
                                <div class="ptp-alert ptp-alert-info">
                                    ℹ️ Get your API keys from <a href="https://dashboard.stripe.com/apikeys" target="_blank">Stripe Dashboard → Developers → API keys</a>
                                </div>
                                
                                <div class="ptp-radio-row">
                                    <label><input type="radio" name="ptp_stripe_mode" value="test" <?php checked(get_option('ptp_stripe_mode', 'test'), 'test'); ?>> Test Mode</label>
                                    <label><input type="radio" name="ptp_stripe_mode" value="live" <?php checked(get_option('ptp_stripe_mode', 'test'), 'live'); ?>> Live Mode</label>
                                </div>
                                
                                <div class="ptp-grid">
                                    <div class="ptp-field">
                                        <label>Publishable Key</label>
                                        <input type="text" name="ptp_stripe_publishable_key" value="<?php echo esc_attr(get_option('ptp_stripe_publishable_key')); ?>" placeholder="pk_test_... or pk_live_...">
                                    </div>
                                    <div class="ptp-field">
                                        <label>Secret Key</label>
                                        <input type="password" name="ptp_stripe_secret_key" value="<?php echo esc_attr(get_option('ptp_stripe_secret_key')); ?>" placeholder="sk_test_... or sk_live_...">
                                    </div>
                                    <div class="ptp-field full">
                                        <label>Webhook Secret</label>
                                        <input type="password" name="ptp_stripe_webhook_secret" value="<?php echo esc_attr(get_option('ptp_stripe_webhook_secret')); ?>" placeholder="whsec_...">
                                        <span class="hint">Webhook URL: <code><?php echo rest_url('ptp-training/v1/stripe-webhook'); ?></code></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ptp-card">
                            <div class="ptp-card-header">
                                <h2>Stripe Connect</h2>
                                <p>Allow trainers to receive direct payments</p>
                            </div>
                            <div class="ptp-card-body">
                                <div class="ptp-toggle-row">
                                    <input type="checkbox" name="ptp_stripe_connect_enabled" value="1" <?php checked(get_option('ptp_stripe_connect_enabled'), 1); ?>>
                                    <span>Enable Stripe Connect for trainer payouts</span>
                                </div>
                                <div class="ptp-field" style="margin-top: 16px;">
                                    <label>Payout Delay (days)</label>
                                    <input type="number" name="ptp_stripe_payout_delay" value="<?php echo esc_attr(get_option('ptp_stripe_payout_delay', 7)); ?>" min="0" max="30" style="max-width: 100px;">
                                    <span class="hint">Days after session before trainer payout</span>
                                </div>
                            </div>
                        </div>

                        <?php elseif ($current_tab === 'notifications'): ?>
                        <!-- NOTIFICATIONS TAB -->
                        <div class="ptp-card">
                            <div class="ptp-card-header">
                                <h2>Email Settings</h2>
                                <p>Configure email sender information</p>
                            </div>
                            <div class="ptp-card-body">
                                <div class="ptp-grid">
                                    <div class="ptp-field">
                                        <label>From Name</label>
                                        <input type="text" name="ptp_notification_from_name" value="<?php echo esc_attr(get_option('ptp_notification_from_name', 'Players Teaching Players')); ?>">
                                    </div>
                                    <div class="ptp-field">
                                        <label>From Email</label>
                                        <input type="email" name="ptp_notification_from_email" value="<?php echo esc_attr(get_option('ptp_notification_from_email', get_option('admin_email'))); ?>">
                                    </div>
                                    <div class="ptp-field">
                                        <label>Admin Notification Email</label>
                                        <input type="email" name="ptp_notification_admin_email" value="<?php echo esc_attr(get_option('ptp_notification_admin_email', get_option('admin_email'))); ?>">
                                    </div>
                                    <div class="ptp-field">
                                        <label>Reply-To Email</label>
                                        <input type="email" name="ptp_notification_reply_to" value="<?php echo esc_attr(get_option('ptp_notification_reply_to')); ?>" placeholder="Optional">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ptp-card">
                            <div class="ptp-card-header">
                                <h2>Notification Triggers</h2>
                                <p>Choose which events send emails</p>
                            </div>
                            <div class="ptp-card-body">
                                <div class="ptp-notify-grid">
                                    <div class="ptp-notify-section">
                                        <h4>Booking Requests</h4>
                                        <div class="ptp-toggle-row"><input type="checkbox" name="ptp_notify_parent_booking_request" value="1" <?php checked(get_option('ptp_notify_parent_booking_request', 1), 1); ?>><span>Email parent</span></div>
                                        <div class="ptp-toggle-row"><input type="checkbox" name="ptp_notify_trainer_booking_request" value="1" <?php checked(get_option('ptp_notify_trainer_booking_request', 1), 1); ?>><span>Email trainer</span></div>
                                        <div class="ptp-toggle-row"><input type="checkbox" name="ptp_notify_admin_booking_request" value="1" <?php checked(get_option('ptp_notify_admin_booking_request', 1), 1); ?>><span>Email admin</span></div>
                                    </div>
                                    <div class="ptp-notify-section">
                                        <h4>Session Confirmed</h4>
                                        <div class="ptp-toggle-row"><input type="checkbox" name="ptp_notify_parent_session_confirmed" value="1" <?php checked(get_option('ptp_notify_parent_session_confirmed', 1), 1); ?>><span>Email parent</span></div>
                                        <div class="ptp-toggle-row"><input type="checkbox" name="ptp_notify_trainer_session_confirmed" value="1" <?php checked(get_option('ptp_notify_trainer_session_confirmed', 1), 1); ?>><span>Email trainer</span></div>
                                    </div>
                                    <div class="ptp-notify-section">
                                        <h4>Cancellations</h4>
                                        <div class="ptp-toggle-row"><input type="checkbox" name="ptp_notify_parent_session_cancelled" value="1" <?php checked(get_option('ptp_notify_parent_session_cancelled', 1), 1); ?>><span>Email parent</span></div>
                                        <div class="ptp-toggle-row"><input type="checkbox" name="ptp_notify_trainer_session_cancelled" value="1" <?php checked(get_option('ptp_notify_trainer_session_cancelled', 1), 1); ?>><span>Email trainer</span></div>
                                    </div>
                                    <div class="ptp-notify-section">
                                        <h4>Completion & Payment</h4>
                                        <div class="ptp-toggle-row"><input type="checkbox" name="ptp_notify_parent_session_completed" value="1" <?php checked(get_option('ptp_notify_parent_session_completed', 1), 1); ?>><span>Email after session</span></div>
                                        <div class="ptp-toggle-row"><input type="checkbox" name="ptp_notify_parent_payment_success" value="1" <?php checked(get_option('ptp_notify_parent_payment_success', 1), 1); ?>><span>Send receipt</span></div>
                                        <div class="ptp-toggle-row"><input type="checkbox" name="ptp_notify_admin_payment_success" value="1" <?php checked(get_option('ptp_notify_admin_payment_success', 1), 1); ?>><span>Notify admin</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ptp-card">
                            <div class="ptp-card-header">
                                <h2>Email Templates</h2>
                                <p>Customize subject lines and content</p>
                            </div>
                            <div class="ptp-card-body">
                                <div class="ptp-grid">
                                    <div class="ptp-field full">
                                        <label>Booking Request - Subject</label>
                                        <input type="text" name="ptp_email_booking_request_subject" value="<?php echo esc_attr(get_option('ptp_email_booking_request_subject', 'We received your PTP private training request')); ?>">
                                    </div>
                                    <div class="ptp-field full">
                                        <label>Booking Request - Intro</label>
                                        <textarea name="ptp_email_booking_request_intro" rows="2"><?php echo esc_textarea(get_option('ptp_email_booking_request_intro', 'Thank you for submitting your training request!')); ?></textarea>
                                    </div>
                                    <div class="ptp-field full">
                                        <label>Session Confirmed - Subject</label>
                                        <input type="text" name="ptp_email_session_confirmed_subject" value="<?php echo esc_attr(get_option('ptp_email_session_confirmed_subject', 'Your training session is confirmed!')); ?>">
                                    </div>
                                    <div class="ptp-field full">
                                        <label>Session Confirmed - Intro</label>
                                        <textarea name="ptp_email_session_confirmed_intro" rows="2"><?php echo esc_textarea(get_option('ptp_email_session_confirmed_intro', 'Great news! Your training session has been confirmed.')); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php elseif ($current_tab === 'sms'): ?>
                        <!-- SMS TAB -->
                        <div class="ptp-card">
                            <div class="ptp-card-header">
                                <h2>Twilio SMS</h2>
                                <p>Send text message notifications</p>
                            </div>
                            <div class="ptp-card-body">
                                <div class="ptp-toggle-row" style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #E5E7EB;">
                                    <input type="checkbox" name="ptp_sms_enabled" value="1" <?php checked(get_option('ptp_sms_enabled'), 1); ?>>
                                    <span><strong>Enable SMS Notifications</strong></span>
                                </div>
                                
                                <div class="ptp-alert ptp-alert-info">
                                    ℹ️ Sign up at <a href="https://www.twilio.com/console" target="_blank">twilio.com/console</a> to get your credentials
                                </div>
                                
                                <div class="ptp-grid">
                                    <div class="ptp-field">
                                        <label>Account SID</label>
                                        <input type="text" name="ptp_twilio_sid" value="<?php echo esc_attr(get_option('ptp_twilio_sid')); ?>" placeholder="ACxxxxxxxx">
                                    </div>
                                    <div class="ptp-field">
                                        <label>Auth Token</label>
                                        <input type="password" name="ptp_twilio_token" value="<?php echo esc_attr(get_option('ptp_twilio_token')); ?>">
                                    </div>
                                    <div class="ptp-field">
                                        <label>Phone Number</label>
                                        <input type="text" name="ptp_twilio_phone" value="<?php echo esc_attr(get_option('ptp_twilio_phone')); ?>" placeholder="+15551234567">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ptp-card">
                            <div class="ptp-card-header">
                                <h2>SMS Triggers</h2>
                                <p>Choose when to send texts</p>
                            </div>
                            <div class="ptp-card-body">
                                <div class="ptp-toggle-row"><input type="checkbox" name="ptp_sms_booking_confirmed" value="1" <?php checked(get_option('ptp_sms_booking_confirmed', 1), 1); ?>><span>Booking confirmed</span></div>
                                <div class="ptp-toggle-row"><input type="checkbox" name="ptp_sms_reminder_24h" value="1" <?php checked(get_option('ptp_sms_reminder_24h', 1), 1); ?>><span>24 hour reminder</span></div>
                                <div class="ptp-toggle-row"><input type="checkbox" name="ptp_sms_reminder_1h" value="1" <?php checked(get_option('ptp_sms_reminder_1h'), 1); ?>><span>1 hour reminder</span></div>
                                <div class="ptp-toggle-row"><input type="checkbox" name="ptp_sms_cancellation" value="1" <?php checked(get_option('ptp_sms_cancellation', 1), 1); ?>><span>Cancellation notice</span></div>
                            </div>
                        </div>

                        <?php elseif ($current_tab === 'integrations'): ?>
                        <!-- INTEGRATIONS TAB -->
                        <div class="ptp-card">
                            <div class="ptp-card-header">
                                <h2>Google Maps</h2>
                                <p>Display trainer locations on maps</p>
                            </div>
                            <div class="ptp-card-body">
                                <div class="ptp-field">
                                    <label>API Key</label>
                                    <input type="text" name="ptp_google_maps_key" value="<?php echo esc_attr(get_option('ptp_google_maps_key')); ?>" placeholder="AIzaSy...">
                                    <span class="hint">Get from <a href="https://console.cloud.google.com/google/maps-apis" target="_blank">Google Cloud Console</a></span>
                                </div>
                            </div>
                        </div>

                        <div class="ptp-card">
                            <div class="ptp-card-header">
                                <h2>Google Calendar</h2>
                                <p>Sync sessions to trainer calendars</p>
                            </div>
                            <div class="ptp-card-body">
                                <div class="ptp-alert ptp-alert-info">
                                    ℹ️ Create OAuth credentials at <a href="https://console.cloud.google.com/apis/credentials" target="_blank">Google Cloud Console</a><br>
                                    Redirect URI: <code><?php echo admin_url('admin.php?page=ptp-training-google-callback'); ?></code>
                                </div>
                                <div class="ptp-grid">
                                    <div class="ptp-field">
                                        <label>Client ID</label>
                                        <input type="text" name="ptp_google_client_id" value="<?php echo esc_attr(get_option('ptp_google_client_id')); ?>" placeholder="xxxxx.apps.googleusercontent.com">
                                    </div>
                                    <div class="ptp-field">
                                        <label>Client Secret</label>
                                        <input type="password" name="ptp_google_client_secret" value="<?php echo esc_attr(get_option('ptp_google_client_secret')); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ptp-card">
                            <div class="ptp-card-header">
                                <h2>HubSpot CRM</h2>
                                <p>Sync customers and sessions</p>
                            </div>
                            <div class="ptp-card-body">
                                <div class="ptp-toggle-row" style="margin-bottom: 16px;">
                                    <input type="checkbox" name="ptp_hubspot_enabled" value="1" <?php checked(get_option('ptp_hubspot_enabled'), 1); ?>>
                                    <span>Enable HubSpot Integration</span>
                                </div>
                                <div class="ptp-field">
                                    <label>Private App Token</label>
                                    <input type="password" name="ptp_hubspot_api_key" value="<?php echo esc_attr(get_option('ptp_hubspot_api_key')); ?>" placeholder="pat-na1-xxxxx">
                                </div>
                            </div>
                        </div>

                        <?php elseif ($current_tab === 'branding'): ?>
                        <!-- BRANDING TAB -->
                        <div class="ptp-card">
                            <div class="ptp-card-header">
                                <h2>Brand Colors</h2>
                                <p>Customize your platform's look</p>
                            </div>
                            <div class="ptp-card-body">
                                <div class="ptp-grid">
                                    <div class="ptp-field">
                                        <label>Primary Color</label>
                                        <input type="color" name="ptp_brand_primary_color" value="<?php echo esc_attr(get_option('ptp_brand_primary_color', '#FCB900')); ?>" style="width: 60px; height: 40px; padding: 4px;">
                                    </div>
                                    <div class="ptp-field">
                                        <label>Secondary Color</label>
                                        <input type="color" name="ptp_brand_secondary_color" value="<?php echo esc_attr(get_option('ptp_brand_secondary_color', '#0E0F11')); ?>" style="width: 60px; height: 40px; padding: 4px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ptp-card">
                            <div class="ptp-card-header">
                                <h2>Social Media</h2>
                                <p>Your organization's social links</p>
                            </div>
                            <div class="ptp-card-body">
                                <div class="ptp-grid">
                                    <div class="ptp-field">
                                        <label>Instagram</label>
                                        <div class="ptp-input-group">
                                            <span class="prefix">instagram.com/</span>
                                            <input type="text" name="ptp_social_instagram" value="<?php echo esc_attr(get_option('ptp_social_instagram')); ?>" placeholder="ptpsoccercamps">
                                        </div>
                                    </div>
                                    <div class="ptp-field">
                                        <label>Facebook</label>
                                        <div class="ptp-input-group">
                                            <span class="prefix">facebook.com/</span>
                                            <input type="text" name="ptp_social_facebook" value="<?php echo esc_attr(get_option('ptp_social_facebook')); ?>">
                                        </div>
                                    </div>
                                    <div class="ptp-field">
                                        <label>Twitter / X</label>
                                        <div class="ptp-input-group">
                                            <span class="prefix">x.com/</span>
                                            <input type="text" name="ptp_social_twitter" value="<?php echo esc_attr(get_option('ptp_social_twitter')); ?>">
                                        </div>
                                    </div>
                                    <div class="ptp-field">
                                        <label>YouTube</label>
                                        <input type="url" name="ptp_social_youtube" value="<?php echo esc_attr(get_option('ptp_social_youtube')); ?>" placeholder="https://youtube.com/@channel">
                                    </div>
                                    <div class="ptp-field">
                                        <label>TikTok</label>
                                        <div class="ptp-input-group">
                                            <span class="prefix">tiktok.com/@</span>
                                            <input type="text" name="ptp_social_tiktok" value="<?php echo esc_attr(get_option('ptp_social_tiktok')); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php elseif ($current_tab === 'advanced'): ?>
                        <!-- ADVANCED TAB -->
                        <div class="ptp-card">
                            <div class="ptp-card-header">
                                <h2>Developer Settings</h2>
                                <p>Advanced options</p>
                            </div>
                            <div class="ptp-card-body">
                                <div class="ptp-toggle-row"><input type="checkbox" name="ptp_debug_mode" value="1" <?php checked(get_option('ptp_debug_mode'), 1); ?>><span>Enable Debug Mode (logs to wp-content/ptp-debug.log)</span></div>
                                <div class="ptp-toggle-row"><input type="checkbox" name="ptp_disable_css" value="1" <?php checked(get_option('ptp_disable_css'), 1); ?>><span>Disable plugin CSS (use your own styles)</span></div>
                            </div>
                        </div>

                        <div class="ptp-card">
                            <div class="ptp-card-header">
                                <h2>System Information</h2>
                                <p>Useful for debugging</p>
                            </div>
                            <div class="ptp-card-body">
                                <table class="ptp-system-table">
                                    <tr><td>Plugin Version</td><td><?php echo PTP_TRAINING_VERSION; ?></td></tr>
                                    <tr><td>WordPress</td><td><?php echo get_bloginfo('version'); ?></td></tr>
                                    <tr><td>PHP</td><td><?php echo phpversion(); ?></td></tr>
                                    <tr><td>REST API</td><td><code><?php echo rest_url('ptp-training/v1/'); ?></code></td></tr>
                                </table>
                            </div>
                        </div>

                        <?php endif; ?>

                        <div class="ptp-settings-footer">
                            <?php submit_button('Save Settings', 'primary', 'submit', false); ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }

    // ==========================================
    // AJAX HANDLERS
    // ==========================================

    /**
     * Update session status via AJAX
     */
    public function ajax_update_session_status() {
        check_ajax_referer('ptp_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Permission denied'));
        }

        $session_id = intval($_POST['session_id']);
        $new_status = sanitize_text_field($_POST['status']);

        $valid_statuses = array('requested', 'confirmed', 'scheduled', 'completed', 'cancelled', 'no_show');
        if (!in_array($new_status, $valid_statuses)) {
            wp_send_json_error(array('message' => 'Invalid status'));
        }

        $result = PTP_Database::update_session_status($session_id, $new_status);

        if ($result !== false) {
            wp_send_json_success(array('message' => 'Status updated'));
        } else {
            wp_send_json_error(array('message' => 'Failed to update status'));
        }
    }

    /**
     * Approve trainer application via AJAX
     */
    public function ajax_approve_trainer() {
        check_ajax_referer('ptp_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Permission denied'));
        }

        global $wpdb;
        $app_id = intval($_POST['app_id']);

        $app = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}ptp_applications WHERE id = %d",
            $app_id
        ));

        if (!$app) {
            wp_send_json_error(array('message' => 'Application not found'));
        }

        // Create user account
        $user_id = wp_create_user($app->email, wp_generate_password(), $app->email);

        if (is_wp_error($user_id)) {
            // User might already exist
            $user = get_user_by('email', $app->email);
            if ($user) {
                $user_id = $user->ID;
            } else {
                wp_send_json_error(array('message' => $user_id->get_error_message()));
            }
        }

        // Set role
        $user = new WP_User($user_id);
        $user->set_role('ptp_trainer');
        wp_update_user(array(
            'ID' => $user_id,
            'first_name' => $app->first_name,
            'last_name' => $app->last_name,
            'display_name' => $app->first_name . ' ' . $app->last_name
        ));

        // Create trainer profile
        $slug = sanitize_title($app->first_name . '-' . $app->last_name);
        $wpdb->insert(
            "{$wpdb->prefix}ptp_trainers",
            array(
                'user_id' => $user_id,
                'status' => 'approved',
                'display_name' => $app->first_name . ' ' . $app->last_name,
                'slug' => $slug,
                'bio' => $app->experience_summary,
                'primary_location_city' => $app->location_city,
                'primary_location_state' => $app->location_state,
                'intro_video_url' => $app->intro_video_url,
                'hourly_rate' => 75
            )
        );

        // Update application status
        $wpdb->update(
            "{$wpdb->prefix}ptp_applications",
            array(
                'status' => 'approved',
                'reviewed_by' => get_current_user_id(),
                'reviewed_at' => current_time('mysql')
            ),
            array('id' => $app_id)
        );

        // Send approval email
        $login_url = wp_login_url(home_url('/trainer-dashboard/'));
        $subject = 'Welcome to PTP! Your trainer application is approved';
        $message = "Hi {$app->first_name},\n\n";
        $message .= "Great news! Your application to become a PTP trainer has been approved.\n\n";
        $message .= "Here's how to get started:\n";
        $message .= "1. Log in to your account: {$login_url}\n";
        $message .= "2. Complete your trainer profile\n";
        $message .= "3. Set up your availability\n";
        $message .= "4. Connect your Stripe account to receive payments\n\n";
        $message .= "Welcome to the team!\nThe PTP Team";

        wp_mail($app->email, $subject, $message);

        wp_send_json_success(array('message' => 'Trainer approved and account created'));
    }

    /**
     * Reject trainer application via AJAX
     */
    public function ajax_reject_trainer() {
        check_ajax_referer('ptp_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Permission denied'));
        }

        global $wpdb;
        $app_id = intval($_POST['app_id']);
        $reason = sanitize_textarea_field($_POST['reason'] ?? '');

        $app = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}ptp_applications WHERE id = %d",
            $app_id
        ));

        if (!$app) {
            wp_send_json_error(array('message' => 'Application not found'));
        }

        $wpdb->update(
            "{$wpdb->prefix}ptp_applications",
            array(
                'status' => 'rejected',
                'admin_notes' => $reason,
                'reviewed_by' => get_current_user_id(),
                'reviewed_at' => current_time('mysql')
            ),
            array('id' => $app_id)
        );

        // Send rejection email
        $subject = 'Update on your PTP trainer application';
        $message = "Hi {$app->first_name},\n\n";
        $message .= "Thank you for your interest in becoming a PTP trainer.\n\n";
        $message .= "After careful review, we've decided not to move forward with your application at this time.\n";
        if ($reason) {
            $message .= "\nFeedback: {$reason}\n";
        }
        $message .= "\nWe encourage you to reapply in the future if circumstances change.\n\n";
        $message .= "Best regards,\nThe PTP Team";

        wp_mail($app->email, $subject, $message);

        wp_send_json_success(array('message' => 'Application rejected'));
    }

    /**
     * Process payout via AJAX
     */
    public function ajax_process_payout() {
        check_ajax_referer('ptp_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Permission denied'));
        }

        $payout_id = intval($_POST['payout_id']);

        $result = PTP_Stripe::process_payout($payout_id);

        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }

        wp_send_json_success(array('message' => 'Payout processed', 'transfer_id' => $result));
    }
}

PTP_Admin::instance();
