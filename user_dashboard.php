<?php
session_start();

if (!isset($_SESSION["user_id"]) || (int) $_SESSION["user_id"] <= 0) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "heavenlybloom");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$userId = (int) $_SESSION["user_id"];
$stmt = $conn->prepare(
    "SELECT Username, Email, phone, wallet_balance FROM users WHERE ID = ?"
);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$userName = htmlspecialchars($user["Username"], ENT_QUOTES, "UTF-8");
$userEmail = htmlspecialchars($user["Email"], ENT_QUOTES, "UTF-8");
$userPhone = htmlspecialchars($user["phone"], ENT_QUOTES, "UTF-8");
$userBalance = number_format((float) $user["wallet_balance"], 2);
$welcomeName = htmlspecialchars(explode(" ", $user["Username"])[0], ENT_QUOTES, "UTF-8");

$_SESSION["username"] = $user["Username"];
$_SESSION["email"] = $user["Email"];
$_SESSION["phone"] = $user["phone"];
$_SESSION["wallet_balance"] = $user["wallet_balance"];
?>
<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Dashboard | Heavenly Blooms</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="CSS/user_dashboard.css" />
    <link rel="stylesheet" href="CSS/nav.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <?php require_once "header.php"; ?>
    <div class="background">
        <div class="liquid liquid-one"></div>
        <div class="liquid liquid-two"></div>
        <div class="liquid liquid-three"></div>
    </div>

    <main class="dashboard">
        <section class="welcome-section">
            <div class="welcome-content">
                <span class="eyebrow">
                    <i class="fa-solid fa-sparkles"></i>
                    Heavenly Blooms
                </span>

                <h1>Welcome back, <span><?= $welcomeName ?></span></h1>

                <p>Here you can check your info and track your orders.</p>
            </div>

            <div class="flower-decoration">
                <i class="fa-solid fa-leaf leaf-one"></i>
                <i class="fa-solid fa-leaf leaf-two"></i>
                <i class="fa-solid fa-seedling flower-icon"></i>
            </div>
        </section>

        <section class="main-grid">
            <div class="panel glass profile-panel">
                <div class="panel-icon">
                    <i class="fa-solid fa-user"></i>
                </div>

                <span class="panel-label">
                    <i class="fa-solid fa-id-card"></i>
                    Profile
                </span>

                <h2>My Info</h2>

                <div class="profile-info">
                    <div class="info-row">
                        <div class="info-icon"><i class="fa-solid fa-user"></i></div>
                        <div class="info-text">
                            <span>Name</span>
                            <strong><?= $userName ?></strong>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-icon"><i class="fa-solid fa-envelope"></i></div>
                        <div class="info-text">
                            <span>Email</span>
                            <strong><?= $userEmail ?></strong>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
                        <div class="info-text">
                            <span>Phone Number</span>
                            <strong><?= $userPhone ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel glass management-panel">
                <div class="management-visual">
                    <div class="visual-circle">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>

                    <div class="visual-leaf leaf-a">
                        <i class="fa-solid fa-leaf"></i>
                    </div>

                    <div class="visual-leaf leaf-b">
                        <i class="fa-solid fa-leaf"></i>
                    </div>
                </div>

                <span class="panel-label">
                    <i class="fa-solid fa-sliders"></i>
                    Shop
                </span>

                <h2>Products</h2>

                <p>Browse our flowers, bouquets, and gifts, and pick what you love.</p>

                <a href="products.php" class="products-btn">
                    Browse Products
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </section>
    </main>
    <?php require_once "footer.php"; ?>
    <script src="JS/nav.js"></script>
</body>

</html>