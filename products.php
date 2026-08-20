<?php
session_start();
require("connection.php");

$isLoggedIn = isset($_SESSION["user_id"]) && (int) $_SESSION["user_id"] > 0;

$query = "SELECT * FROM `products` ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products | Heavenly Blooms</title>

    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/nav.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

    <?php include("header.php"); ?>

    <main>

        <section class="page-hero">
            <div>
                <span class="section-label">HEAVENLY BLOOMS</span>
                <h1>Our Flowers</h1>
                <p>Find the perfect flowers for every beautiful moment.</p>
            </div>
        </section>

        <section class="products-section">

            <div class="section-heading">
                <span class="section-label">OUR COLLECTION</span>
                <h2>Choose Your Favorite</h2>
                <p>Explore our beautiful collection of fresh flowers.</p>
            </div>

            <div class="products-grid" id="productsGrid">

                <?php if ($result && mysqli_num_rows($result) > 0) { ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <article class="product-card">
                            <div class="product-image">
                                <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                <span>Popular</span>
                            </div>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                                <p><?php echo htmlspecialchars($row['description']); ?></p>
                                <div class="product-bottom">
                                    <strong>$<?php echo number_format($row['price'], 2); ?></strong>
                                    <button type="button"><i class="fa-solid fa-cart-plus"></i></button>
                                </div>
                            </div>
                        </article>
                    <?php } ?>
                <?php } else { ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 50px 20px; color: #8e7c85;">
                        <i class="fa-solid fa-seedling" style="font-size: 2.5rem; color: #b85c75; margin-bottom: 12px; display: block;"></i>
                        <h3>No products available yet</h3>
                        <p>Please check back soon for our latest flower collection.</p>
                    </div>
                <?php } ?>

            </div>

        </section>

    </main>

    <?php include("footer.php"); ?>

    <script>
        window.IS_LOGGED_IN = <?php echo json_encode($isLoggedIn); ?>;
    </script>
    <script src="JS/products.js"></script>
    <script src="JS/nav.js"></script>

</body>

</html>