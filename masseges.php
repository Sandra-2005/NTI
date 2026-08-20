<?php
session_start();
require("connection.php");
$query = "SELECT * FROM `messages` ORDER BY `id` DESC";
$result = mysqli_query($conn, $query);
$total_count = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages | Heavenly Blooms Admin</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="CSS/nav_admin.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/massages.css">
</head>

<body>
    <?php include 'header-admin.php'; ?>
    <div class="app-layout">
        <main class="main-content-container">
            <div class="dashboard-card">
                <header class="card-header">
                    <div class="header-left">
                        <div class="page-title">
                            <div class="title-icon">
                                <i class="fa-solid fa-message"></i>
                            </div>
                            <div>
                                <h1>Contact Messages</h1>
                                <p class="subtitle">Review and manage customer feedback and inquiries</p>
                            </div>
                        </div>
                    </div>

                    <div class="admin-profile">
                        <div class="admin-avatar">A</div>
                        <div class="admin-info">
                            <strong>Admin</strong>
                            <span>System Manager</span>
                        </div>
                    </div>
                </header>

                <section class="stats-row">
                    <div class="stat-card">
                        <div>
                            <h2 id="totalMessages"><?= $total_count ?></h2>
                            <span>Total Received Messages</span>
                        </div>
                        <div class="stat-icon">
                            <i class="fa-solid fa-message"></i>
                        </div>
                    </div>
                </section>

                <section class="controls">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="searchInput" placeholder="Search by name, phone, or message..."
                            autocomplete="off">
                        <button id="clearSearchBtn" type="button" aria-label="Clear Search"><i
                                class="fa-solid fa-xmark"></i></button>
                    </div>
                </section>

                <section class="messages-container">
                    <div class="messages-header">
                        <div>SENDER</div>
                        <div>PHONE NUMBER</div>
                        <div>MESSAGE</div>
                    </div>

                    <div class="messages-list" id="messagesList">

                        <?php if ($total_count > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <?php $avatar = strtoupper(substr($row['sender_name'], 0, 1)); ?>
                                <div class="message-row" data-id="<?= $row['id'] ?>" data-name="<?= htmlspecialchars($row['sender_name']) ?>" data-phone="<?= htmlspecialchars($row['phone']) ?>" data-text="<?= htmlspecialchars($row['message']) ?>">
                                    <div class="sender-col">
                                        <div class="avatar"><?= $avatar ?></div>
                                        <span class="sender-name"><?= htmlspecialchars($row['sender_name']) ?></span>
                                    </div>
                                    <div class="phone-col">
                                        <i class="fa-solid fa-phone"></i>
                                        <a href="tel:<?= htmlspecialchars($row['phone']) ?>" onclick="event.stopPropagation()">
                                            <?= htmlspecialchars($row['phone']) ?>
                                        </a>
                                    </div>
                                    <div class="msg-col"><?= htmlspecialchars($row['message']) ?></div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <h4>No messages received yet.</h4>
                            </div>
                        <?php endif; ?>

                    </div>
                </section>

            </div>
        </main>

    </div>

    <div class="modal" id="messageModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Message Details</h3>
                <button class="close-btn" id="closeModalBtn" type="button">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="sender-info">
                    <div>
                        <label>Sender Name</label>
                        <p id="modalName">-</p>
                    </div>
                    <div>
                        <label>Phone Number</label>
                        <p id="modalPhone">-</p>
                    </div>
                </div>

                <div class="message-content">
                    <label>Full Message</label>
                    <div class="message-text" id="modalText"></div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-cancel" id="cancelModalBtn" type="button">Close</button>
                <a class="btn-call" id="modalCallLink" href="#" target="_blank">
                    <i class="fa-solid fa-phone"></i>
                    Call Customer
                </a>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="JS/masseages.js"></script>
    <script src="JS/nav_admin.js"></script>
</body>

</html>