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
    
    <a href="#" class="nav-item">
        <i class="ri-layout-left-line"></i> <span>Nav & Sidebar</span>
    </a>

    <div class="nav-category" style="margin-top: 20px;">Components</div>
    
    <a href="#" class="nav-item">
        <i class="ri-table-line"></i> <span>Tables</span>
    </a>
    <a href="#" class="nav-item">
        <i class="ri-notification-badge-line"></i> <span>Notifications</span>
    </a>
    <a href="#" class="nav-item">
        <i class="ri-alert-line"></i> <span>Alerts</span>
    </a>

    <div class="nav-item" style="margin-top: auto;">
        <i class="ri-settings-4-line"></i> <span>Settings</span>
    </div>
</nav>