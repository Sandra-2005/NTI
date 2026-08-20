<?php
session_start();

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_GET["action"])
    && $_GET["action"] === "place_order"
) {
    header("Content-Type: application/json; charset=utf-8");

    if (!isset($_SESSION["user_id"]) || (int) $_SESSION["user_id"] <= 0) {
        http_response_code(401);
        echo json_encode([
            "success" => false,
            "message" => "Please log in to place an order.",
        ]);
        exit;
    }

    $payload = json_decode(file_get_contents("php://input"), true);

    if (!is_array($payload)) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Invalid order data.",
        ]);
        exit;
    }

    $customerName = trim($payload["firstName"] ?? "");
    $customerEmail = trim($payload["email"] ?? "");
    $customerPhone = trim($payload["phone"] ?? "");
    $cart = $payload["cart"] ?? [];

    if ($customerName === "" || $customerEmail === "" || $customerPhone === "") {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Please fill in all required fields.",
        ]);
        exit;
    }

    if (!is_array($cart) || count($cart) === 0) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Your cart is empty.",
        ]);
        exit;
    }

    $normalizedItems = [];
    $totalAmount = 0.0;

    foreach ($cart as $item) {
        if (!is_array($item)) {
            continue;
        }

        $productName = trim($item["name"] ?? "");
        $productImage = trim($item["image"] ?? "");
        $price = (float) ($item["price"] ?? 0);
        $quantity = (int) ($item["quantity"] ?? 1);

        if ($productName === "" || $price <= 0 || $quantity <= 0) {
            continue;
        }

        $lineTotal = $price * $quantity;
        $totalAmount += $lineTotal;

        $normalizedItems[] = [
            "name" => $productName,
            "image" => $productImage,
            "price" => $price,
            "quantity" => $quantity,
        ];
    }

    if (count($normalizedItems) === 0 || $totalAmount <= 0) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "No valid products found in your cart.",
        ]);
        exit;
    }

    require_once __DIR__ . "/connection.php";

    $userId = (int) $_SESSION["user_id"];
    $orderNumber = "HB-" . substr((string) time(), -8);

    $conn->begin_transaction();

    try {
        $balanceStmt = $conn->prepare(
            "SELECT wallet_balance FROM users WHERE ID = ? FOR UPDATE"
        );
        $balanceStmt->bind_param("i", $userId);
        $balanceStmt->execute();
        $balanceResult = $balanceStmt->get_result();
        $userRow = $balanceResult->fetch_assoc();
        $balanceStmt->close();

        if (!$userRow) {
            throw new Exception("User account not found.");
        }

        $currentBalance = (float) $userRow["wallet_balance"];

        if ($currentBalance < $totalAmount) {
            $conn->rollback();
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Insufficient wallet balance. Please recharge your wallet.",
            ]);
            exit;
        }

        $newBalance = $currentBalance - $totalAmount;

        $updateStmt = $conn->prepare(
            "UPDATE users SET wallet_balance = ? WHERE ID = ?"
        );
        $updateStmt->bind_param("di", $newBalance, $userId);
        $updateStmt->execute();
        $updateStmt->close();
        $conn->commit();

        $_SESSION["wallet_balance"] = $newBalance;

        echo json_encode([
            "success" => true,
            "orderNumber" => $orderNumber,
            "total" => $totalAmount,
            "newBalance" => $newBalance,
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Could not place your order. Please try again.",
        ]);
    }

    exit;
}

if (!isset($_SESSION["user_id"]) || (int) $_SESSION["user_id"] <= 0) {
    header("Location: login.php");
    exit;
}

$userEmail = "";
$userPhone = "";
$userName = "";
$walletBalance = 0.0;
$isLoggedIn = false;

if (isset($_SESSION["user_id"]) && (int) $_SESSION["user_id"] > 0) {
    $conn = new mysqli("localhost", "root", "", "heavenlybloom");
    if (!$conn->connect_error) {
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

        if ($user) {
            $isLoggedIn = true;
            $userEmail = htmlspecialchars($user["Email"], ENT_QUOTES, "UTF-8");
            $userPhone = htmlspecialchars($user["phone"], ENT_QUOTES, "UTF-8");
            $walletBalance = (float) $user["wallet_balance"];

            $userName = htmlspecialchars($user["Username"], ENT_QUOTES, "UTF-8");

            $_SESSION["username"] = $user["Username"];
            $_SESSION["email"] = $user["Email"];
            $_SESSION["phone"] = $user["phone"];
            $_SESSION["wallet_balance"] = $user["wallet_balance"];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Heavenly Blooms</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/nav.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/check_out.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <?php require_once "header.php"; ?>
    <main>

        <section class="page-hero">

            <div>
                <span class="section-label">HEAVENLY BLOOMS</span>
                <h1>Checkout</h1>
                <p>Complete your order and let the flowers do the talking.</p>
            </div>

        </section>

        <section class="checkout-section">

            <div class="checkout-grid">

                <div class="checkout-form">

                    <div class="checkout-card">
                        <h2>Customer Information</h2>

                        <div class="field-grid">
                            <div class="field">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" value="<?= $userEmail ?>">
                                <p class="field-error" id="errEmail"></p>
                            </div>

                            <div class="field">
                                <label for="phone">Phone Number *</label>
                                <input type="tel" id="phone" value="<?= $userPhone ?>">
                                <p class="field-error" id="errPhone"></p>
                            </div>

                            <div class="field">
                                <label for="firstName">Name *</label>
                                <input type="text" id="firstName" value="<?= $userName ?>">
                                <p class="field-error" id="errFirstName"></p>
                            </div>
                        </div>
                    </div>

                    <div class="checkout-card">
                        <h2>Payment Method</h2>

                        <div class="wallet-payment">
                            <div class="wallet-payment-icon">
                                <i class="fa-solid fa-wallet"></i>
                            </div>

                            <div class="wallet-payment-info">
                                <strong>Wallet Balance</strong>
                                <span id="walletBalanceDisplay">0.00 EGP</span>
                            </div>
                        </div>

                        <p class="wallet-note" id="walletNote"></p>
                    </div>

                </div>

                <div class="checkout-summary">

                    <div class="checkout-card">
                        <h2>Order Summary</h2>

                        <div class="cart-items" id="cartItems"></div>

                        <div class="checkout-totals">
                            <div class="totals-row">
                                <span>Subtotal</span>
                                <span id="subtotalValue">0.00 EGP</span>
                            </div>

                            <div class="totals-row totals-grand">
                                <span>Total</span>
                                <span id="totalValue">0.00 EGP</span>
                            </div>

                            <div class="totals-row totals-remaining">
                                <span>Remaining Balance</span>
                                <span id="remainingBalanceValue">0.00 EGP</span>
                            </div>
                        </div>
                    </div>

                    <button class="place-order-btn" id="placeOrderBtn">
                        Place Order
                    </button>

                </div>

            </div>

        </section>

    </main>

    <div class="checkout-modal" id="successModal">
        <div class="checkout-modal-box">

            <div class="checkout-modal-icon">
                <i class="fa-solid fa-check"></i>
            </div>

            <h2>Order Placed Successfully!</h2>
            <p>Thank you for your order. Your beautiful blooms are on their way.</p>

            <div class="checkout-modal-summary">
                <div>
                    <span>Order Number</span>
                    <strong id="modalOrderId"></strong>
                </div>

                <div>
                    <span>Total</span>
                    <strong id="modalTotal"></strong>
                </div>

                <div>
                    <span>Payment</span>
                    <strong>Wallet Balance</strong>
                </div>
            </div>

            <div class="checkout-modal-actions">
                <a href="products.php" class="primary-btn">Continue Shopping</a>
            </div>

        </div>
    </div>
    <?php require_once "footer.php"; ?>
    <script>
        window.IS_LOGGED_IN = <?= json_encode($isLoggedIn) ?>;
    </script>
    <?php if ($isLoggedIn): ?>
        <script>
            window.SERVER_WALLET_BALANCE = <?= json_encode($walletBalance) ?>;
        </script>
    <?php endif; ?>
    <script src="JS/check_out.js"></script>
    <script src="JS/nav.js"></script>
</body>

</html>