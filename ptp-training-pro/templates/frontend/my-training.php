<?php
/**
 * My Training Dashboard Template - TeachMe.to Style v5.1
 * Nuclear CSS version with #ptp-app wrapper
 */

defined('ABSPATH') || exit;

// Check if user is logged in
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

$current_user = wp_get_current_user();
$first_name = $current_user->first_name ?: $current_user->display_name;
?>

<div class="ptp-my-training-wrap">
<div id="ptp-app" class="ptp-dashboard">
    
    <!-- Dashboard Header -->
    <header class="ptp-dash-header">
        <div class="ptp-dash-header-inner">
            <a href="<?php echo home_url(); ?>" class="ptp-dash-logo">
                <span class="ptp-dash-logo-icon">
                    <img src="https://ptpsoccercamps.com/wp-content/uploads/2023/07/ptp-logo.png" alt="PTP" onerror="this.parentElement.innerHTML='⚽'">
                </span>
                PTP Training
            </a>
            
            <nav class="ptp-dash-nav">
                <a href="<?php echo home_url('/my-training/'); ?>" class="active">My Training</a>
                <a href="<?php echo home_url('/find-trainers/'); ?>">Find Trainers</a>
                <a href="<?php echo home_url('/messages/'); ?>">Messages</a>
            </nav>
            
            <div class="ptp-dash-actions">
                <a href="<?php echo home_url('/find-trainers/'); ?>" class="ptp-dash-btn">Book Session</a>
            </div>
        </div>
    </header>
    
    <!-- Welcome Section -->
    <section class="ptp-welcome">
        <div class="ptp-welcome-inner">
            <h1>Welcome back, <?php echo esc_html($first_name); ?>! 👋</h1>
            <p>Here's what's happening with your soccer training</p>
        </div>
    </section>
    
    <!-- Stats Cards -->
    <div class="ptp-stats-row">
        <div class="ptp-stat-card">
            <h3>12</h3>
            <p>Sessions Completed</p>
        </div>
        <div class="ptp-stat-card">
            <h3>3</h3>
            <p>Upcoming Sessions</p>
        </div>
        <div class="ptp-stat-card">
            <h3>2</h3>
            <p>Active Athletes</p>
        </div>
        <div class="ptp-stat-card">
            <h3>$340</h3>
            <p>Credits Available</p>
        </div>
    </div>
    
    <!-- Dashboard Layout -->
    <div class="ptp-dash-layout">
        
        <!-- Sidebar -->
        <aside class="ptp-sidebar">
            <div class="ptp-sidebar-card">
                <h3>Navigation</h3>
                <nav class="ptp-sidebar-nav">
                    <a href="#" class="ptp-sidebar-nav-item active">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        Dashboard
                    </a>
                    <a href="#" class="ptp-sidebar-nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                        Upcoming
                        <span class="badge">3</span>
                    </a>
                    <a href="#" class="ptp-sidebar-nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        My Athletes
                    </a>
                    <a href="#" class="ptp-sidebar-nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        Messages
                        <span class="badge">2</span>
                    </a>
                    <a href="#" class="ptp-sidebar-nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path></svg>
                        Notifications
                    </a>
                    <a href="#" class="ptp-sidebar-nav-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        Settings
                    </a>
                </nav>
            </div>
            
            <div class="ptp-sidebar-card">
                <h3>Training Packs</h3>
                <div style="margin-bottom: 16px !important;">
                    <div style="display: flex !important; justify-content: space-between !important; font-size: 14px !important; margin-bottom: 6px !important;">
                        <span style="font-weight: 600 !important; color: #0E0F11 !important;">5-Session Pack</span>
                        <span style="color: #6B7280 !important;">2/5 used</span>
                    </div>
                    <div style="height: 8px !important; background: #E5E7EB !important; border-radius: 100px !important; overflow: hidden !important;">
                        <div style="width: 40% !important; height: 100% !important; background: #FCB900 !important; border-radius: 100px !important;"></div>
                    </div>
                </div>
                <a href="<?php echo home_url('/find-trainers/'); ?>" style="display: inline-flex !important; align-items: center !important; gap: 6px !important; color: #2563EB !important; font-size: 14px !important; font-weight: 600 !important; text-decoration: none !important;">
                    Buy more sessions
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                </a>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="ptp-content">
            
            <!-- Upcoming Sessions -->
            <div class="ptp-content-card">
                <div class="ptp-content-header">
                    <h2>Upcoming Sessions</h2>
                    <a href="#" class="ptp-view-link">View all</a>
                </div>
                <div class="ptp-content-body">
                    <div class="ptp-session-card">
                        <img src="https://ptpsummercamps.com/wp-content/uploads/2025/12/BG7A1787.jpg" alt="Marcus Johnson">
                        <div class="ptp-session-info">
                            <h4>Training with Marcus Johnson</h4>
                            <p>Jake (Age 12) • Ball Mastery Focus</p>
                            <p class="ptp-session-time">Tomorrow, Dec 6 @ 4:00 PM</p>
                            <p>📍 Radnor Memorial Park</p>
                        </div>
                    </div>
                    
                    <div class="ptp-session-card" style="margin-top: 16px !important;">
                        <img src="https://ptpsummercamps.com/wp-content/uploads/2025/12/BG7A1730.jpg" alt="Tyler Rodriguez">
                        <div class="ptp-session-info">
                            <h4>Training with Tyler Rodriguez</h4>
                            <p>Emma (Age 14) • Finishing Skills</p>
                            <p class="ptp-session-time">Saturday, Dec 7 @ 10:00 AM</p>
                            <p>📍 Wayne Sports Complex</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="ptp-content-card">
                <div class="ptp-content-header">
                    <h2>Recent Activity</h2>
                    <a href="#" class="ptp-view-link">View all</a>
                </div>
                <div class="ptp-content-body">
                    <div style="display: flex !important; flex-direction: column !important; gap: 16px !important;">
                        <div style="display: flex !important; align-items: center !important; gap: 16px !important; padding: 16px !important; background: #F9FAFB !important; border-radius: 12px !important;">
                            <div style="width: 44px !important; height: 44px !important; background: #D1FAE5 !important; border-radius: 50% !important; display: flex !important; align-items: center !important; justify-content: center !important; font-size: 20px !important;">✓</div>
                            <div style="flex: 1 !important;">
                                <div style="font-weight: 600 !important; color: #0E0F11 !important; font-size: 15px !important;">Session Completed</div>
                                <div style="font-size: 13px !important; color: #6B7280 !important;">Training with Marcus Johnson • Dec 3</div>
                            </div>
                        </div>
                        <div style="display: flex !important; align-items: center !important; gap: 16px !important; padding: 16px !important; background: #F9FAFB !important; border-radius: 12px !important;">
                            <div style="width: 44px !important; height: 44px !important; background: #FEF9E6 !important; border-radius: 50% !important; display: flex !important; align-items: center !important; justify-content: center !important; font-size: 20px !important;">📅</div>
                            <div style="flex: 1 !important;">
                                <div style="font-weight: 600 !important; color: #0E0F11 !important; font-size: 15px !important;">Session Booked</div>
                                <div style="font-size: 13px !important; color: #6B7280 !important;">Tyler Rodriguez for Emma • Dec 7</div>
                            </div>
                        </div>
                        <div style="display: flex !important; align-items: center !important; gap: 16px !important; padding: 16px !important; background: #F9FAFB !important; border-radius: 12px !important;">
                            <div style="width: 44px !important; height: 44px !important; background: #EFF6FF !important; border-radius: 50% !important; display: flex !important; align-items: center !important; justify-content: center !important; font-size: 20px !important;">💬</div>
                            <div style="flex: 1 !important;">
                                <div style="font-weight: 600 !important; color: #0E0F11 !important; font-size: 15px !important;">New Message</div>
                                <div style="font-size: 13px !important; color: #6B7280 !important;">Marcus Johnson sent a session summary</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
    
</div>
</div>
