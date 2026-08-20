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
    $username = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";
    if (
        $username === "" ||
        $email === "" ||
        $phone === "" ||
        $password === "" ||
        $confirmPassword === ""
    ) {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $stmt = $conn->prepare(
            "SELECT ID FROM users WHERE Email = ?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $error = "This email is already registered.";
        } else {
            $stmt->close();
            $stmt = $conn->prepare(
                "SELECT ID FROM users WHERE Username = ?"
            );
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $error = "This username is already taken.";
            } else {
                $hashedPassword = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );
                $stmt->close();
                $stmt = $conn->prepare(
                    "INSERT INTO users
                    (Username, Email, phone, Password, wallet_balance)
                    VALUES (?, ?, ?, ?, 0.00)"
                );
                $stmt->bind_param(
                    "ssss",
                    $username,
                    $email,
                    $phone,
                    $hashedPassword
                );
                if ($stmt->execute()) {
                    session_regenerate_id(true);
                    $_SESSION["user_id"] = $stmt->insert_id;
                    $_SESSION["username"] = $username;
                    $_SESSION["email"] = $email;
                    $_SESSION["phone"] = $phone;
                    $_SESSION["wallet_balance"] = 0.00;
                    $_SESSION["role"] = "user";
                    header("Location: user_dashboard.php");
                    exit;
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
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
    <title>Register - Heavenly Bloom 🌸</title>
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
        <div class="auth-card auth-card--wide">
            <div class="auth-card__brand">
                🌸 Heavenly Bloom
            </div>
            <span class="auth-card__ornament">
                ❦
            </span>
            <h2>
                Create Account
            </h2>
            <p>
                Join us to explore & send flowers to your loved ones
            </p>
            <?php if (!empty($error)): ?>
                <div class="alert error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <form
                id="registerForm"
                action="register.php"
                method="POST">
                <div class="form-group">
                    <label>
                        Full Name
                    </label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        required
                        placeholder="e.g. Enter your full name">
                </div>
                <div class="form-row">
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
                            Phone Number
                        </label>
                        <input
                            type="tel"
                            name="phone"
                            id="phone"
                            required
                            placeholder="01*********">
                    </div>
                </div>
                <div class="form-row">
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
                    <div class="form-group">
                        <label>
                            Confirm Password
                        </label>
                        <input
                            type="password"
                            name="confirm_password"
                            id="confirm_password"
                            required
                            placeholder="••••••••">
                    </div>
                </div>
                <button
                    type="submit"
                    class="btn-primary">
                    Register
                </button>
            </form>
            <p class="switch-auth">
                Already have an account?
                <a href="login.php">
                    Sign In
                </a>
            </p>
        </div>
    </div>
    <?php require_once "footer.php"; ?>
    <script src="JS/login&register.js"></script>
    <script src="JS/nav.js"></script>
</body>

</html>