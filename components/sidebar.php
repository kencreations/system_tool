<?php
// Get current filename to set active state
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="app-sidebar">
    <div class="app-brand">
        <i class="ri-code-box-line text-primary"></i> DevStudio
    </div>

    <div class="nav-category">Core Generators</div>
    
    <a href="index.php" class="nav-item <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
        <i class="ri-article-line"></i> <span>Form Builder</span>
    </a>
    
    <a href="cards.php" class="nav-item <?php echo ($current_page == 'cards.php') ? 'active' : ''; ?>">
        <i class="ri-layout-masonry-line"></i> <span>Card Positioning</span>
    </a>
    
    <a href="navigation.php" class="nav-item <?php echo ($current_page == 'navigation.php') ? 'active' : ''; ?>">
        <i class="ri-layout-left-line"></i> <span>Nav & Sidebar</span>
    </a>
    <a href="forms_advanced.php" class="nav-item <?php echo ($current_page == 'forms_advanced.php') ? 'active' : ''; ?>">
    <i class="ri-toggle-line"></i> <span>Advanced Forms</span>
</a>

<div class="nav-category" style="margin-top: 20px;">Pages</div>

<a href="pages.php" class="nav-item <?php echo ($current_page == 'pages.php') ? 'active' : ''; ?>">
    <i class="ri-pages-line"></i> <span>Auth & Templates</span>
</a>
<a href="profile.php" class="nav-item <?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>">
    <i class="ri-user-settings-line"></i> <span>Profile Settings</span>
</a>
<div class="nav-category" style="margin-top: 20px;">Backend</div>

<a href="backend.php" class="nav-item <?php echo ($current_page == 'backend.php') ? 'active' : ''; ?>">
    <i class="ri-server-line"></i> <span>Backend API</span>
</a>
<div class="nav-category" style="margin-top: 20px;">Widgets & Visuals</div>

<a href="stats.php" class="nav-item <?php echo ($current_page == 'stats.php') ? 'active' : ''; ?>">
    <i class="ri-bar-chart-2-line"></i> <span>Stats Cards</span>
</a>
<a href="charts.php" class="nav-item <?php echo ($current_page == 'charts.php') ? 'active' : ''; ?>">
    <i class="ri-pie-chart-line"></i> <span>Charts</span>
</a>


    <div class="nav-category" style="margin-top: 20px;">Components</div>
    <a href="timeline.php" class="nav-item <?php echo ($current_page == 'timeline.php') ? 'active' : ''; ?>">
    <i class="ri-git-commit-line"></i> <span>Timeline</span>
</a>
    <a href="#" class="nav-item">
        <i class="ri-table-line"></i> <span>Tables</span>
    </a>
    <a href="modals.php" class="nav-item <?php echo ($current_page == 'modals.php') ? 'active' : ''; ?>">
    <i class="ri-window-line"></i> <span>Modals</span>
</a>
    <a href="notifications.php" class="nav-item">
        <i class="ri-notification-badge-line"></i> <span>Notifications</span>
    </a>
    <a href="alerts.php" class="nav-item">
        <i class="ri-alert-line"></i> <span>Alerts</span>
    </a><div class="nav-category" style="margin-top: 20px;">SaaS Modules</div>

<a href="kanban.php" class="nav-item <?php echo ($current_page == 'kanban.php') ? 'active' : ''; ?>">
    <i class="ri-kanban-view"></i> <span>Kanban Board</span>
</a>
<a href="pricing.php" class="nav-item <?php echo ($current_page == 'pricing.php') ? 'active' : ''; ?>">
    <i class="ri-price-tag-3-line"></i> <span>Pricing Tables</span>
</a>

    <div class="nav-item" style="margin-top: auto;">
        <i class="ri-settings-4-line"></i> <span>Settings</span>
    </div>
</nav>