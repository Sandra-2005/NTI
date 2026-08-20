<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/connection.php";

$generateError = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "generate_code") {
    $amountRaw = $_POST["amount"] ?? "";
    $jsCode = trim($_POST["generated_code"] ?? "");

    if ($amountRaw === "" || !is_numeric($amountRaw)) {
        $generateError = "Invalid amount";
    } elseif ((float) $amountRaw <= 0) {
        $generateError = "Amount must be greater than 0";
    } else {
        $amount = (float) $amountRaw;

        $isValidFormat = (bool) preg_match('/^[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/', $jsCode);

        if (!$isValidFormat) {
            $generateError = "Invalid code, please try again";
        } else {
            $checkStmt = $conn->prepare("SELECT id FROM wallet WHERE code = ?");
            $checkStmt->bind_param("s", $jsCode);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows > 0) {
                $generateError = "Code already exists, please try again";
            } else {
                $insertStmt = $conn->prepare("INSERT INTO wallet (code, price) VALUES (?, ?)");
                $insertStmt->bind_param("sd", $jsCode, $amount);

                if (!$insertStmt->execute()) {
                    $generateError = "Failed to save the code";
                }

                $insertStmt->close();
            }

            $checkStmt->close();
        }
    }
}

$totalsResult = $conn->query("SELECT COUNT(*) AS total_codes, COALESCE(SUM(price), 0) AS total_balance FROM wallet");
$totals = $totalsResult->fetch_assoc();

$totalCodes = (int) $totals["total_codes"];
$totalBalance = number_format((float) $totals["total_balance"], 2, ".", "");

$recentCodes = [];
$recentResult = $conn->query("SELECT code, price FROM wallet ORDER BY id DESC LIMIT 20");

if ($recentResult) {
    while ($row = $recentResult->fetch_assoc()) {
        $recentCodes[] = $row;
    }
}

$conn->close();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard | Heavenly Blooms</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Noto+Kufi+Arabic:wght@500;600;700;800&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="CSS/admin_dashboard.css" />
    <link rel="stylesheet" href="CSS/footer.css" />
    <link rel="stylesheet" href="CSS/nav_admin.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>

<body>
    <?php require_once "header-admin.php"; ?>
    <div class="background">
        <div class="liquid liquid-one"></div>
        <div class="liquid liquid-two"></div>
        <div class="liquid liquid-three"></div>
    </div>

    <main class="dashboard">
        <header class="topbar glass">
            <div class="brand">
                <div class="brand-icon">
                    <i class="fa-solid fa-seedling"></i>
                </div>

                <div>
                    <h2>Heavenly Blooms</h2>
                    <span>Admin Panel</span>
                </div>
            </div>

            <div class="admin-profile">
                <div class="admin-info">
                    <strong>Admin</strong>
                    <span>Control Panel</span>
                </div>

                <div class="admin-avatar">
                    <i class="fa-solid fa-user"></i>
                </div>
            </div>
        </header>

        <section class="welcome-section">
            <div class="welcome-content">
                <span class="eyebrow">
                    <i class="fa-solid fa-sparkles"></i>
                    Heavenly Blooms
                </span>

                <h1>Welcome to the Admin panel</h1>

                <p>From here you can manage the wallet, codes, and products with ease.</p>

                <div class="last-login">
                    <i class="fa-regular fa-clock"></i>
                    <span>Last login:</span>
                    <strong id="lastLogin">Loading...</strong>
                </div>
            </div>

            <div class="flower-decoration">
                <i class="fa-solid fa-leaf leaf-one"></i>
                <i class="fa-solid fa-leaf leaf-two"></i>
                <i class="fa-solid fa-seedling flower-icon"></i>
            </div>
        </section>

        <section class="stats-grid">
            <div class="stat-card glass">
                <div class="stat-icon wallet-icon">
                    <i class="fa-solid fa-wallet"></i>
                </div>

                <div class="stat-content">
                    <span>Wallet Balance</span>
                    <strong><span id="walletBalance"><?= htmlspecialchars($totalBalance) ?></span> <small>$</small></strong>
                </div>
            </div>

            <div class="stat-card glass">
                <div class="stat-icon code-icon">
                    <i class="fa-solid fa-ticket"></i>
                </div>

                <div class="stat-content">
                    <span>Generated Codes</span>
                    <strong id="codesCount"><?= (int) $totalCodes ?></strong>
                </div>
            </div>

            <div class="stat-card glass">
                <div class="stat-icon product-icon">
                    <i class="fa-solid fa-box-open"></i>
                </div>

                <div class="stat-content">
                    <span>Product Management</span>
                    <strong>Products</strong>
                </div>
            </div>
        </section>

        <section class="main-grid">
            <div class="panel glass wallet-panel">
                <div class="panel-header">
                    <div>
                        <span class="panel-label">
                            <i class="fa-solid fa-wallet"></i>
                            Wallet
                        </span>

                        <h2>Generate Recharge Code</h2>
                        <p>Create a new code with the required value.</p>
                    </div>

                    <div class="panel-icon">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>
                </div>

                <?php if (!empty($generateError)): ?>
                    <div class="alert error"><?= htmlspecialchars($generateError) ?></div>
                <?php endif; ?>

                <form class="code-form" id="codeForm" action="admin_dashboard.php" method="POST">
                    <input type="hidden" name="action" value="generate_code" />
                    <input type="hidden" name="generated_code" id="generatedCodeField" />

                    <label for="codeAmount">Code Value</label>

                    <div class="amount-wrapper">
                        <input type="number" name="amount" id="codeAmount" min="1" placeholder="e.g. 100" />
                        <span>$</span>
                    </div>

                    <button type="submit" class="generate-btn" id="generateCode">
                        <i class="fa-solid fa-plus"></i>
                        Generate Code
                    </button>
                </form>

                <div class="generated-section">
                    <div class="generated-header">
                        <h3>Recent Codes</h3>
                        <span id="generatedCount"><?= (int) $totalCodes ?> codes</span>
                    </div>

                    <div class="codes-list" id="codesList">
                        <?php foreach ($recentCodes as $item): ?>
                            <div class="code-item">
                                <div class="code-data">
                                    <div class="code-symbol">
                                        <i class="fa-solid fa-key"></i>
                                    </div>

                                    <div class="code-value">
                                        <strong><?= htmlspecialchars($item["code"]) ?></strong>
                                        <span> New Charge Code </span>
                                    </div>
                                </div>

                                <div class="code-price">
                                    <?= htmlspecialchars(number_format((float) $item["price"], 2, ".", "")) ?> $
                                </div>
                            </div>
                        <?php endforeach; ?>
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
                    Management
                </span>

                <h2>Product Management</h2>

                <p>Add products, edit details, and keep track of the products in the store.</p>

                <a href="manange-product.php" class="products-btn">
                    Manage Products
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>
        </section>
    </main>
    <?php require_once "footer.php"; ?>
    <script src="JS/nav_admin.js"></script>
    <script src="JS/admin_dashboard.js"></script>
</body>

</html>