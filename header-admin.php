<header class="top-nav-bar">
    <div class="nav-container">

        <div class="nav-left">
            <a href="admin_dashboard.php" class="navbar-logo">
                <i class="fa-solid fa-seedling logo-icon"></i>
                <span class="logo-text">Heavenly <span class="logo-accent">Blooms</span></span>
            </a>
        </div>

        <nav class="nav-center" id="navCenterMenu">
            <a href="admin_dashboard.php" class="nav-item">Dashboard</a>
            <a href="manange-product.php" class="nav-item">Products</a>
            <a href="masseges.php" class="nav-item">Contact Messages</a>
        </nav>

        <div class="nav-right">
            <a href="login.php" class="logout-pill-btn">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Logout</span>
            </a>

            <button class="nav-toggle-btn" id="navToggleBtn" type="button" aria-label="Toggle Navigation">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

    </div>

    <div class="mobile-nav-menu" id="mobileNavMenu">
        <div class="mobile-nav-links">
            <a href="admin_dashboard.php" class="mobile-nav-item">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>
            <a href="admin_products.php" class="mobile-nav-item active">
                <i class="fa-solid fa-box"></i>
                <span>Products</span>
            </a>
            <a href="masseges.php" class="mobile-nav-item">
                <i class="fa-solid fa-message"></i>
                <span>Contact Messages</span>
            </a>
        </div>
        <div class="mobile-nav-footer">
            <a href="login.php" class="logout-pill-btn mobile-logout">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
    <div class="mobile-overlay" id="mobileNavOverlay"></div>
</header>