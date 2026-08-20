<?php
session_start();
require("connection.php");

$msg = "";

if ($_POST) {
    $destination = "";
    if (isset($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {
        $destination = "IMG/" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $destination);
    }

    $name = addslashes($_POST['name']);
    $price = addslashes($_POST['price']);
    $description = addslashes($_POST['description']);

    $query = "INSERT INTO `products` (`name`, `description`, `price`, `image_path`) 
              VALUES ('$name', '$description', '$price', '$destination')";

    $result = mysqli_query($conn, $query);

    if ($result) {
        header("location:manange-product.php");
        exit;
    } else {
        $msg = "Error adding product: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | Heavenly Blooms Admin</title>

    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/nav_admin.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/add_product.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

    <?php include("header-admin.php"); ?>

    <main>

        <section class="page-hero">
            <div>
                <span class="section-label">ADMIN PANEL</span>
                <h1>Add New Product</h1>
                <p>Fill in the details below to add a new flower to your store.</p>
            </div>
        </section>

        <section class="add-product-section">
            <div class="add-product-card">

                <?php if (!empty($msg)) { ?>
                    <p class="form-alert"><?php echo $msg; ?></p>
                <?php } ?>

                <form id="addProductForm" action="" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label>Product Image</label>
                        <input type="file" id="productImageFile" name="image" accept="image/*" style="display: none;" required>
                        <div class="file-upload-box" id="fileUploadBox">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <strong id="uploadText">Click to choose an image from your device</strong>
                            <span>Supports JPEG, PNG, WebP</span>
                        </div>
                        <div class="image-preview-wrapper" id="previewWrapper">
                            <img id="imagePreview" src="" alt="Selected Preview">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="productName">Product Name</label>
                        <input type="text" id="productName" name="name" class="form-input" placeholder="e.g. Royal Velvet Roses" required>
                    </div>

                    <div class="form-group">
                        <label for="productPrice">Price ($)</label>
                        <input type="number" id="productPrice" name="price" class="form-input" step="0.01" min="1" placeholder="$49.99" required>
                    </div>

                    <div class="form-group">
                        <label for="productDescription">Description</label>
                        <textarea id="productDescription" name="description" class="form-textarea" placeholder="Enter product description..." rows="4"></textarea>
                    </div>

                    <div class="form-actions">
                        <a href="manange-product.php" class="btn-cancel">Cancel</a>
                        <button type="submit" class="btn-submit">
                            <i class="fa-solid fa-plus"></i>
                            Add Product
                        </button>
                    </div>

                </form>

            </div>
        </section>

    </main>

    <?php include("footer.php"); ?>

    <script src="JS/nav_admin.js"></script>
    <script src="JS/add_product.js"></script>

</body>

</html>