<?php require("connection.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Heavenly Blooms | Beautiful Flowers</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/nav.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

    <?php require_once "header.php"; ?>

    <main>

        <section class="hero">

            <div class="hero-slider">

                <div class="hero-slide active">
                    <div class="hero-content">
                        <span class="hero-small">WELCOME TO HEAVENLY BLOOMS</span>
                        <h1>Flowers That Speak From The Heart</h1>
                        <p>
                            Discover beautiful hand-picked flowers designed
                            to make every moment unforgettable.
                        </p>

                        <div class="hero-buttons">
                            <a href="products.php" class="primary-btn">
                                Shop Now
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>

                            <a href="about.php" class="secondary-btn">
                                Discover More
                            </a>
                        </div>
                    </div>

                    <div class="hero-image">
                        <img src="IMG/f1.png" alt="Beautiful flowers">
                    </div>
                </div>

                <div class="hero-slide">
                    <div class="hero-content">
                        <span class="hero-small">FRESH FLOWERS EVERY DAY</span>
                        <h1>Make Every Day Bloom</h1>
                        <p>
                            Fresh and elegant arrangements created with love
                            for your special occasions.
                        </p>

                        <div class="hero-buttons">
                            <a href="products.php" class="primary-btn">
                                Explore Flowers
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>

                            <a href="work.php" class="secondary-btn">
                                Our Work
                            </a>
                        </div>
                    </div>

                    <div class="hero-image">
                        <img src="IMG/f2.jpeg" alt="Flower arrangement">
                    </div>
                </div>

                <div class="hero-slide">
                    <div class="hero-content">
                        <span class="hero-small">SPECIAL MOMENTS</span>
                        <h1>A Little Flower, A Lot Of Love</h1>
                        <p>
                            Send someone special a beautiful bouquet
                            that says everything without words.
                        </p>

                        <div class="hero-buttons">
                            <a href="products.php" class="primary-btn">
                                Shop Collection
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>

                            <a href="about.php" class="secondary-btn">
                                About Us
                            </a>
                        </div>
                    </div>

                    <div class="hero-image">
                        <img src="IMG/f3.jpeg" alt="Pink flowers">
                    </div>
                </div>

                <div class="hero-slide">
                    <div class="hero-content">
                        <span class="hero-small">PREMIUM COLLECTION</span>
                        <h1>Elegant Flowers For Elegant Moments</h1>
                        <p>
                            Premium floral designs carefully created
                            for weddings, celebrations and unforgettable gifts.
                        </p>

                        <div class="hero-buttons">
                            <a href="products.php" class="primary-btn">
                                View Products
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>

                            <a href="work.php" class="secondary-btn">
                                See Our Work
                            </a>
                        </div>
                    </div>

                    <div class="hero-image">
                        <img src="IMG/f4.jpeg" alt="Premium flowers">
                    </div>
                </div>

            </div>

            <button class="slider-btn prev" id="prevSlide">
                <i class="fa-solid fa-chevron-left"></i>
            </button>

            <button class="slider-btn next" id="nextSlide">
                <i class="fa-solid fa-chevron-right"></i>
            </button>

            <div class="slider-dots" id="sliderDots"></div>

        </section>

        <section class="about-preview">

            <div class="about-image">
                <img src="IMG/f5.jpeg" alt="Flowers">
            </div>

            <div class="about-content">

                <span class="section-label">WHY HEAVENLY BLOOMS?</span>

                <h2>We Turn Beautiful Flowers Into Beautiful Memories</h2>

                <p>
                    At Heavenly Blooms, we believe that flowers are more than
                    beautiful decorations. They are a way to express love,
                    happiness, gratitude and unforgettable emotions.
                </p>

                <div class="features">

                    <div class="feature">
                        <div class="feature-icon">
                            <i class="fa-solid fa-leaf"></i>
                        </div>

                        <div>
                            <h3>Fresh Flowers</h3>
                            <p>Freshly selected flowers every morning.</p>
                        </div>
                    </div>

                    <div class="feature">
                        <div class="feature-icon">
                            <i class="fa-solid fa-heart"></i>
                        </div>

                        <div>
                            <h3>Made With Love</h3>
                            <p>Every bouquet is carefully arranged.</p>
                        </div>
                    </div>

                    <div class="feature">
                        <div class="feature-icon">
                            <i class="fa-solid fa-gift"></i>
                        </div>

                        <div>
                            <h3>Perfect Gifts</h3>
                            <p>Beautiful gifts for every occasion.</p>
                        </div>
                    </div>

                    <div class="feature">
                        <div class="feature-icon">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>

                        <div>
                            <h3>Fast Delivery</h3>
                            <p>We deliver your flowers safely and quickly.</p>
                        </div>
                    </div>

                </div>

                <a href="about.php" class="primary-btn">
                    Learn More
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

            </div>

        </section>

        <section class="stats-section">

            <div class="stat-box">
                <i class="fa-solid fa-seedling"></i>
                <strong>250+</strong>
                <span>Flower Types</span>
            </div>

            <div class="stat-box">
                <i class="fa-solid fa-users"></i>
                <strong>5K+</strong>
                <span>Happy Customers</span>
            </div>

            <div class="stat-box">
                <i class="fa-solid fa-star"></i>
                <strong>4.9</strong>
                <span>Customer Rating</span>
            </div>

            <div class="stat-box">
                <i class="fa-solid fa-heart"></i>
                <strong>10K+</strong>
                <span>Beautiful Bouquets</span>
            </div>

            <div class="stat-box">
                <i class="fa-solid fa-truck-fast"></i>
                <strong>24/7</strong>
                <span>Fast Delivery</span>
            </div>

        </section>

        <section class="featured-section">

            <div class="section-heading">
                <span class="section-label">OUR COLLECTION</span>
                <h2>Popular Flowers</h2>
                <p>Discover some of our most loved floral arrangements.</p>
            </div>

            <div class="featured-grid">

                <?php

                $sql = "SELECT * FROM products ORDER BY id DESC LIMIT 3";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0):

                    while ($product = $result->fetch_assoc()):

                ?>

                        <article class="featured-card">

                            <div class="featured-image">

                                <img
                                    src="<?php echo htmlspecialchars($product['image_path']); ?>"
                                    alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <span>Popular</span>

                            </div>

                            <div class="featured-info">

                                <h3>
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </h3>

                                <p>
                                    <?php echo htmlspecialchars($product['description']); ?>
                                </p>

                                <strong>
                                    $<?php echo number_format($product['price'], 2); ?>
                                </strong>

                            </div>

                        </article>

                    <?php

                    endwhile;

                else:

                    ?>

                    <p>No products available.</p>

                <?php endif; ?>

            </div>

            <section class="cta-section">

                <div class="cta-content">
                    <span class="section-label">MAKE SOMEONE HAPPY</span>
                    <h2>Send Flowers. Share Happiness.</h2>
                    <p>
                        Choose the perfect bouquet and make someone's day
                        a little more beautiful.
                    </p>

                    <a href="products.php" class="primary-btn">
                        Shop Flowers
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

            </section>

    </main>

    <?php require_once "footer.php"; ?>
    <script src="JS/nav.js"></script>
    <script src="JS/script.js"></script>
</body>

</html>