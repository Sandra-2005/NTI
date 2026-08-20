<?php
session_start();
require("connection.php");

if (isset($_POST['delete-product'])) {
    $id = addslashes($_POST['id']);
    $query = "DELETE FROM `products` WHERE id = '$id'";
    mysqli_query($conn, $query);
    header("location:manange-product.php");
    exit;
}

$query = "SELECT * FROM `products` ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products | Heavenly Blooms Admin</title>

    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/nav_admin.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/manage_product.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <?php include("header-admin.php"); ?>
    <main>

        <section class="page-hero">
            <div>
                <span class="section-label">ADMIN PANEL</span>
                <h1>Manage Products</h1>
                <p>Add new flowers or remove existing items from the store.</p>
            </div>
        </section>

        <section class="products-section">

            <div class="admin-toolbar">
                <a href="add_product.php" class="add-product-btn">
                    <i class="fa-solid fa-plus"></i>
                    Add Product
                </a>
            </div>

            <div class="products-grid" id="adminProductsGrid">
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
                                    <form action="" method="post" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="delete-product" class="delete-btn" title="Delete Product">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    <?php } ?>
                <?php } else { ?>
                    <div class="empty-products">
                        <i class="fa-solid fa-seedling"></i>
                        <h3>No products found</h3>
                        <p>Click Add Product above to create a new product.</p>
                    </div>
                <?php } ?>
            </div>

        </section>

    </main>

    <?php include("footer.php"); ?>

    <script src="JS/nav_admin.js"></script>
    <script src="JS/manage_product.js"></script>

</body>

</html>