<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cur_page = basename($_SERVER['PHP_SELF']);

$is_logged_in = isset($_SESSION['user_id']);
$is_user = $is_logged_in && ($_SESSION['role'] ?? '') === 'user';
$is_admin = $is_logged_in && ($_SESSION['role'] ?? '') === 'admin';
?>

<header class="navbar">
    <div class="nav-container">
        <a href="index.php" class="logo">
            <i class="fa-solid fa-seedling"></i>
            <span>Heavenly <b>Blooms</b></span>
        </a>

        <?php if ($is_user): ?>

            <nav class="nav-links" id="navLinks">

                <a href="index.php"
                    class="<?= $cur_page == 'index.php' ? 'active' : '' ?>">
                    Home
                </a>

                <a href="products.php"
                    class="<?= $cur_page == 'products.php' ? 'active' : '' ?>">
                    Products
                </a>

                <a href="about.php"
                    class="<?= $cur_page == 'about.php' ? 'active' : '' ?>">
                    About
                </a>

                <a href="logout.php" class="nav-shop">
                    Logout
                </a>

            </nav>

            <div class="balance-box">

                <a href="wallet.php" class="balance-link">
                    <button class="balance-plus" type="button">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </a>

                <div class="balance-info">
                    <span>Balance</span>

                    <strong>
                        <?= htmlspecialchars(
                            number_format(
                                (float)($_SESSION['wallet_balance'] ?? 0),
                                2
                            )
                        ) ?>
                        EGP
                    </strong>
                </div>

            </div>

        <?php elseif ($is_admin): ?>

            <nav class="nav-links" id="navLinks">

                <a href="admin_dashboard.php"
                    class="<?= $cur_page == 'admin_dashboard.php' ? 'active' : '' ?>">
                    Dashboard
                </a>

                <a href="logout.php" class="nav-shop">
                    Logout
                </a>

            </nav>

        <?php else: ?>

            <nav class="nav-links" id="navLinks">

                <a href="index.php"
                    class="<?= $cur_page == 'index.php' ? 'active' : '' ?>">
                    Home
                </a>

                <a href="products.php"
                    class="<?= $cur_page == 'products.php' ? 'active' : '' ?>">
                    Products
                </a>

                <a href="work.php"
                    class="<?= $cur_page == 'work.php' ? 'active' : '' ?>">
                    Our Work
                </a>

                <a href="about.php"
                    class="<?= $cur_page == 'about.php' ? 'active' : '' ?>">
                    About
                </a>

                <a href="login.php" class="nav-shop">
                    Login
                </a>

                <a href="register.php" class="nav-shop">
                    Register
                </a>

            </nav>

        <?php endif; ?>

        <div class="nav-actions">
            <?php if ($is_user): ?>
                <a href="user_dashboard.php"
                    class="nav-dashboard-icon<?= $cur_page == 'user_dashboard.php' ? ' active' : '' ?>"
                    title="Dashboard"
                    aria-label="User Dashboard">
                    <i class="fa-solid fa-user"></i>
                </a>
            <?php endif; ?>
            <button class="menu-btn" id="menuBtn" type="button">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

    </div>
</header>