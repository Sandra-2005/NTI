<?php
session_start();
require("connection.php");

$msg = "";
if ($_POST) {
    $name = addslashes($_POST['name']);
    $phone = addslashes($_POST['phone']);
    $message = addslashes($_POST['message']);

    $user_id = "NULL";
    if (isset($_SESSION['user']['ID'])) {
        $user_id = "'" . intval($_SESSION['user']['ID']) . "'";
    } elseif (isset($_SESSION['user']['id'])) {
        $user_id = "'" . intval($_SESSION['user']['id']) . "'";
    }

    $query = "INSERT INTO `messages` (`user_id`, `sender_name`, `phone`, `message`) VALUES ($user_id, '$name', '$phone', '$message')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $msg = "Thank you! Your message has been sent successfully.";
    } else {
        $msg = "Failed to send message: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Heavenly Blooms</title>

    <link rel="stylesheet" href="CSS/nav.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

    <?php include 'header.php'; ?>

    <main>

        <section class="page-hero">

            <div>
                <span class="section-label">OUR STORY</span>
                <h1>About Heavenly Blooms</h1>
                <p>Where flowers become memories.</p>
            </div>

        </section>

        <section class="about-page-section">

            <div class="about-page-image">
                <img src="IMG/WhatsApp Image 2026-08-14 at 11.59.41 PM (1).jpeg" alt="Heavenly Blooms">
            </div>

            <div class="about-page-content">

                <span class="section-label">WHO WE ARE</span>

                <h2>We Believe Every Moment Deserves To Bloom</h2>

                <p>
                    Heavenly Blooms was created with one simple idea:
                    flowers can make people feel something special.
                </p>

                <p>
                    From romantic roses to elegant wedding arrangements,
                    we carefully select and arrange every flower to create
                    beautiful experiences for our customers.
                </p>

                <p>
                    Our goal is to make it easy for you to find the perfect
                    flowers for birthdays, weddings, anniversaries, celebrations
                    and all the little moments that matter.
                </p>

                <a href="products.php" class="primary-btn">
                    Explore Our Flowers
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

            </div>

        </section>

        <section class="values-section">

            <div class="section-heading">
                <span class="section-label">OUR VALUES</span>
                <h2>What Makes Us Different?</h2>
            </div>

            <div class="values-grid">

                <div class="value-card">
                    <div class="value-icon">
                        <i class="fa-solid fa-leaf"></i>
                    </div>

                    <h3>Freshness</h3>

                    <p>
                        We choose fresh flowers to make sure every arrangement
                        looks beautiful and lasts longer.
                    </p>
                </div>

                <div class="value-card">
                    <div class="value-icon">
                        <i class="fa-solid fa-heart"></i>
                    </div>

                    <h3>Passion</h3>

                    <p>
                        Every bouquet is created with attention, creativity
                        and genuine love for flowers.
                    </p>
                </div>

                <div class="value-card">
                    <div class="value-icon">
                        <i class="fa-solid fa-star"></i>
                    </div>

                    <h3>Quality</h3>

                    <p>
                        We focus on delivering high-quality flowers and
                        beautiful arrangements every time.
                    </p>
                </div>

            </div>

        </section>

        <section class="contact-section" id="contact">

            <div class="contact-info">

                <span class="section-label">GET IN TOUCH</span>

                <h2>Let's Create Something Beautiful</h2>

                <p>
                    Have a question or want to create a special flower arrangement?
                    Send us a message and our team will get back to you.
                </p>

                <div class="contact-details">

                    <div class="contact-item">
                        <div>
                            <i class="fa-solid fa-location-dot"></i>
                        </div>

                        <div>
                            <h3>Our Location</h3>
                            <p>Cairo, Egypt</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div>
                            <i class="fa-solid fa-phone"></i>
                        </div>

                        <div>
                            <h3>Phone</h3>
                            <p>+20 110 444 3663</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div>
                            <i class="fa-solid fa-envelope"></i>
                        </div>

                        <div>
                            <h3>Email</h3>
                            <p>hello@heavenlyblooms.com</p>
                        </div>
                    </div>

                </div>

            </div>

            <form class="contact-form" id="contactForm" action="" method="POST">

                <?php if (!empty($msg)): ?>
                    <p style="color: #b85c75; font-weight: 600; text-align: center; margin-bottom: 15px;"><?= $msg ?></p>
                <?php endif; ?>

                <div class="input-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Your name" value="<?php echo isset($_SESSION['user']['Username']) ? htmlspecialchars($_SESSION['user']['Username']) : ''; ?>" required>
                </div>

                <div class="input-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" placeholder="01*********" value="<?php echo isset($_SESSION['user']['phone']) ? htmlspecialchars($_SESSION['user']['phone']) : ''; ?>" required>
                </div>

                <div class="input-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="6" placeholder="Write your message" required></textarea>
                </div>

                <button type="submit" class="primary-btn">
                    Send Message
                    <i class="fa-solid fa-paper-plane"></i>
                </button>

            </form>

        </section>

    </main>

    <?php include 'footer.php'; ?>

    <script src="JS/nav.js"></script>
    <script src="JS/script.js"></script>

</body>

</html>