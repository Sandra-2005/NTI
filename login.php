<?php
session_start();
$error = "";
$conn = new mysqli(
    "localhost",
    "root",
    "",
    "heavenlybloom"
);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    if ($email === "" || $password === "") {
        $error = "Please enter your email and password.";
    } elseif (
        $email === "admin@gmail.com" &&
        $password === "admin123"
    ) {
        session_regenerate_id(true);
        $_SESSION["user_id"] = 0;
        $_SESSION["username"] = "Admin";
        $_SESSION["email"] = "admin@gmail.com";
        $_SESSION["role"] = "admin";
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $stmt = $conn->prepare(
            "SELECT ID, Username, Email, phone, Password, wallet_balance
             FROM users
             WHERE Email = ?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user["Password"])) {
                session_regenerate_id(true);
                $_SESSION["user_id"] = $user["ID"];
                $_SESSION["username"] = $user["Username"];
                $_SESSION["email"] = $user["Email"];
                $_SESSION["phone"] = $user["phone"];
                $_SESSION["wallet_balance"] = $user["wallet_balance"];
                $_SESSION["role"] = "user";
                header("Location: user_dashboard.php");
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">
    <title>Login - Heavenly Bloom 🌸</title>
    <link
        rel="stylesheet"
        href="CSS/login&register.css">
    <link
        rel="stylesheet"
        href="CSS/nav.css">
    <link
        rel="stylesheet"
        href="CSS/footer.css">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <?php require_once "header.php"; ?>
    <div class="auth-page">
        <div class="auth-card">
            <div class="auth-card__brand">
                🌸 Heavenly Bloom
            </div>
            <span class="auth-card__ornament">
                ❦
            </span>
            <h2>
                Welcome Back
            </h2>
            <p>
                Please log in to track your orders and gifts
            </p>
            <?php if (!empty($error)): ?>
                <div class="alert error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <form
                id="loginForm"
                action="login.php"
                method="POST">
                <div class="form-group">
                    <label>
                        Email Address
                    </label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        required
                        placeholder="name@example.com">
                </div>
                <div class="form-group">
                    <label>
                        Password
                    </label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        placeholder="••••••••">
                </div>
                <button
                    type="submit"
                    class="btn-primary">
                    Sign In
                </button>
            </form>
            <p class="switch-auth">
                Don't have an account?
                <a href="register.php">
                    Create one
                </a>
            </p>
        </div>
    </div>
    <?php require_once "footer.php"; ?>
    <script src="JS/login&register.js"></script>
    <script src="JS/nav.js"></script>
</body>

</html>