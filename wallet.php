<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "user") {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/connection.php";

$rechargeError = "";
$rechargeSuccess = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "recharge") {
    $rawCode = trim($_POST["code"] ?? "");
    $upperCode = strtoupper($rawCode);

    $cleanedCode = preg_replace('/[^A-Z0-9]/', '', $upperCode);

    if ($cleanedCode === "") {
        $rechargeError = "Please Enter Charge Code";
        $code = "";
    } elseif (strlen($cleanedCode) !== 12) {
        $rechargeError = "Code Not Correct Or Find";
        $code = "";
    } else {
        $code = implode("-", str_split($cleanedCode, 4));
    }

    if (!empty($rechargeError)) {
    } elseif (!preg_match('/^[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/', $code)) {
        $rechargeError = "Code Not Correct Or Find";
    } else {
        $userId = $_SESSION["user_id"];

        $conn->begin_transaction();

        try {
            $findStmt = $conn->prepare("SELECT id, price FROM wallet WHERE code = ? FOR UPDATE");
            $findStmt->bind_param("s", $code);
            $findStmt->execute();
            $result = $findStmt->get_result();

            if ($result->num_rows === 0) {
                $findStmt->close();
                $conn->rollback();
                $rechargeError = "Code Not Correct Or Find";
            } else {
                $codeRow = $result->fetch_assoc();
                $findStmt->close();

                $amount = (float) $codeRow["price"];
                $codeId = (int) $codeRow["id"];

                $updateStmt = $conn->prepare("UPDATE users SET wallet_balance = wallet_balance + ? WHERE ID = ?");
                $updateStmt->bind_param("di", $amount, $userId);
                $updateStmt->execute();
                $updateStmt->close();

                $deleteStmt = $conn->prepare("DELETE FROM wallet WHERE id = ?");
                $deleteStmt->bind_param("i", $codeId);
                $deleteStmt->execute();
                $deleteStmt->close();

                $insertStmt = $conn->prepare("INSERT INTO transactions (user_id, code, amount) VALUES (?, ?, ?)");
                $insertStmt->bind_param("isd", $userId, $code, $amount);
                $insertStmt->execute();
                $insertStmt->close();

                $conn->commit();

                $_SESSION["wallet_balance"] = (float) $_SESSION["wallet_balance"] + $amount;

                $rechargeSuccess = "Charge " . number_format($amount, 2) . " Succesed $.";
            }
        } catch (Exception $e) {
            $conn->rollback();
            $rechargeError = "Something went wrong, please try again";
        }
    }
}

$balanceStmt = $conn->prepare("SELECT wallet_balance FROM users WHERE ID = ?");
$balanceStmt->bind_param("i", $_SESSION["user_id"]);
$balanceStmt->execute();
$balanceResult = $balanceStmt->get_result();
$balanceRow = $balanceResult->fetch_assoc();
$balanceStmt->close();

$currentBalance = $balanceRow ? (float) $balanceRow["wallet_balance"] : 0;
$_SESSION["wallet_balance"] = $currentBalance;

$transactions = [];
$txStmt = $conn->prepare("SELECT code, amount, created_at FROM transactions WHERE user_id = ? ORDER BY id DESC LIMIT 20");
$txStmt->bind_param("i", $_SESSION["user_id"]);
$txStmt->execute();
$txResult = $txStmt->get_result();

while ($row = $txResult->fetch_assoc()) {
    $transactions[] = $row;
}

$txStmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Wallet | Heavenly Blooms</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Noto+Kufi+Arabic:wght@500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="CSS/wallet.css">
    <link rel="stylesheet" href="CSS/nav.css">
    <link rel="stylesheet" href="CSS/footer.css">
</head>

<body>
    <?php require_once "header.php"; ?>

    <div class="background">
        <div class="blob blob-one"></div>
        <div class="blob blob-two"></div>
        <div class="blob blob-three"></div>
    </div>

    <main class="wallet-page">

        <header class="topbar glass">

            <a href="user_dashboard.php" class="brand">
                <div class="brand-icon">
                    <i class="fa-solid fa-seedling"></i>
                </div>

                <div>
                    <h2>Heavenly Blooms</h2>
                    <span>Flower Store</span>
                </div>
            </a>

            <a href="user_dashboard.php" class="back-btn">
                Dashboard
                <i class="fa-solid fa-arrow-left"></i>
            </a>

        </header>

        <section class="wallet-hero">

            <div class="hero-content">

                <span class="eyebrow">
                    <i class="fa-solid fa-wallet"></i>
                    My Wallet
                </span>

                <h1>Your Wallet</h1>

                <p>
                    Recharge your balance using a top-up code and enjoy a fast, easy shopping experience.
                </p>

            </div>

            <div class="balance-card glass">

                <div class="balance-top">
                    <span>Current Balance</span>

                    <div class="balance-icon">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                </div>

                <div class="balance-value">
                    <strong id="balance"><?= htmlspecialchars(number_format($currentBalance, 0)) ?></strong>
                    <span>$</span>
                </div>

                <div class="balance-footer">
                    <span>
                        <i class="fa-solid fa-circle-check"></i>
                        Wallet Active
                    </span>

                    <span>Heavenly Blooms</span>
                </div>

            </div>

        </section>

        <section class="content-grid">

            <div class="recharge-card glass">

                <div class="section-heading">

                    <div>
                        <span class="section-label">
                            <i class="fa-solid fa-bolt"></i>
                            Recharge
                        </span>

                        <h2>Recharge Wallet</h2>

                        <p>
                            Enter the top-up code you have to add balance.
                        </p>
                    </div>

                    <div class="heading-icon">
                        <i class="fa-solid fa-ticket"></i>
                    </div>

                </div>

                <form id="rechargeForm" action="wallet.php" method="POST">

                    <input type="hidden" name="action" value="recharge" />

                    <label for="rechargeCode">
                        Recharge Code
                    </label>

                    <div class="code-input">

                        <i class="fa-solid fa-key"></i>

                        <input type="text" name="code" id="rechargeCode" placeholder="XXXX-XXXX-XXXX" maxlength="14"
                            autocomplete="off">

                    </div>

                    <button type="submit" class="recharge-btn">
                        <i class="fa-solid fa-plus"></i>
                        Add Balance
                    </button>

                </form>

                <div class="message <?= !empty($rechargeError) ? "error" : (!empty($rechargeSuccess) ? "success" : "") ?>"
                    id="message">
                    <?= htmlspecialchars($rechargeError ?: $rechargeSuccess) ?>
                </div>

                <div class="recharge-info">

                    <div class="info-item">
                        <div>
                            <i class="fa-solid fa-shield-heart"></i>
                        </div>

                        <span>
                            All recharge operations are secure
                        </span>
                    </div>

                    <div class="info-item">
                        <div>
                            <i class="fa-solid fa-clock"></i>
                        </div>

                        <span>
                            Balance is added instantly
                        </span>
                    </div>

                </div>

            </div>

            <div class="flower-card">

                <div class="flower-overlay"></div>

                <div class="flower-content">
                    <div class="flower-icon">
                        <i class="fa-solid fa-spa"></i>
                    </div>

                    <span>Heavenly Blooms</span>

                    <h2>
                        Bring your flower
                        <br>
                        closer to you
                    </h2>

                    <p>
                        Use your balance to buy the most beautiful products and gifts.
                    </p>

                </div>

            </div>

        </section>

        <section class="transactions glass">

            <div class="transactions-heading">

                <div>
                    <span class="section-label">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        History
                    </span>

                    <h2>Recent Transactions</h2>
                </div>

                <span class="transactions-count" id="transactionsCount">
                    <?= count($transactions) ?> <?= count($transactions) === 1 ? "transaction" : "transactions" ?>
                </span>

            </div>

            <div class="transactions-list" id="transactionsList">

                <?php if (empty($transactions)): ?>
                    <div class="empty-state" id="emptyState">
                        <div>
                            <i class="fa-solid fa-receipt"></i>
                        </div>

                        <span>No recharge transactions yet</span>
                    </div>
                <?php else: ?>
                    <?php foreach ($transactions as $tx): ?>
                        <div class="transaction">
                            <div class="transaction-info">
                                <div class="transaction-icon">
                                    <i class="fa-solid fa-arrow-down"></i>
                                </div>

                                <div>
                                    <strong>Charge </strong>
                                    <span>
                                        <?= htmlspecialchars(date("j F, H:i", strtotime($tx["created_at"]))) ?>
                                        ·
                                        <?= htmlspecialchars($tx["code"]) ?>
                                    </span>
                                </div>
                            </div>

                            <div class="transaction-amount">
                                +<?= htmlspecialchars(number_format((float) $tx["amount"], 0)) ?> $
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>

        </section>
    </main>
    <?php require_once "footer.php"; ?>
    <script src="JS/wallet.js"></script>
    <script src="JS/nav.js"></script>
</body>

</html>