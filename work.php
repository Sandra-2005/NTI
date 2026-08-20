<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Work | Heavenly Blooms</title>

    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/nav.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <?php require_once "header.php"; ?>

    <main>

        <section class="page-hero">

            <div>
                <span class="section-label">OUR PORTFOLIO</span>
                <h1>Our Work</h1>
                <p>Beautiful floral designs created for beautiful moments.</p>
            </div>

        </section>

        <section class="work-section">

            <div class="work-tabs">

                <button class="work-tab active" data-work="all">
                    All
                </button>

                <button class="work-tab" data-work="wedding">
                    Wedding
                </button>

                <button class="work-tab" data-work="events">
                    Events
                </button>

                <button class="work-tab" data-work="gifts">
                    Gifts
                </button>

            </div>

            <div class="work-grid">

                <div class="work-card" data-work="wedding">
                    <img src="IMG/WhatsApp Image 2026-08-14 at 11.59.42 PM (1).jpeg" alt="Wedding flowers">
                    <div class="work-overlay">
                        <span>Wedding</span>
                        <h3>Elegant Wedding</h3>
                    </div>
                </div>

                <div class="work-card" data-work="events">
                    <img src="IMG/WhatsApp Image 2026-08-14 at 11.59.42 PM (5).jpeg" alt="Event flowers">
                    <div class="work-overlay">
                        <span>Events</span>
                        <h3>Special Celebration</h3>
                    </div>
                </div>

                <div class="work-card" data-work="gifts">
                    <img src="IMG/WhatsApp Image 2026-08-14 at 11.59.41 PM.jpeg" alt="Flower gift">
                    <div class="work-overlay">
                        <span>Gifts</span>
                        <h3>Luxury Gift</h3>
                    </div>
                </div>

                <div class="work-card" data-work="wedding">
                    <img src="IMG/f1.png" alt="Wedding roses">
                    <div class="work-overlay">
                        <span>Wedding</span>
                        <h3>Romantic Roses</h3>
                    </div>
                </div>

                <div class="work-card" data-work="events">
                    <img src="IMG/WhatsApp Image 2026-08-14 at 11.59.42 PM (2).jpeg" alt="Event bouquet">
                    <div class="work-overlay">
                        <span>Events</span>
                        <h3>Garden Celebration</h3>
                    </div>
                </div>

                <div class="work-card" data-work="gifts">
                    <img src="IMG/WhatsApp Image 2026-08-14 at 11.59.42 PM (4).jpeg" alt="Flower gift">
                    <div class="work-overlay">
                        <span>Gifts</span>
                        <h3>Birthday Surprise</h3>
                    </div>
                </div>

            </div>

        </section>

    </main>
    <?php require_once "footer.php"; ?>

    <script src="JS/script.js"></script>
    <script src="JS/nav.js"></script>
</body>

</html>