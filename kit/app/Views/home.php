<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="<?php echo base_url('styles.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body style="background-color:white;">
    <header>
        <nav class="navbar">
            <div class="logo">
                <a href="home">K<span>Concert</span>Kit</a>
            </div>
            <div class="search-bar position-relative">
                <form action="<?= site_url('search') ?>" method="GET">
                    <input type="text" name="query" placeholder="Search products..." required>
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <div class="nav-icons">
                <?php if (session()->get('role') == '1'): ?>
                    <a href="/keranjang_admin" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                <?php else: ?>
                    <a href="/keranjang/<?= $id_user ?>" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count"><?= $cartCount ?></span>
                    </a>
                <?php endif; ?>
                <a href="/profile" class="profile-icon">
                    <i class="fas fa-user"></i>
                </a>
            </div>
        </nav>
    </header>

    <section class="home" id="home">
        <div class="content">
        
    <section class="welcome-section">
        <h1>WELCOME!</h1>
    </section>

    <section class="about-us">
        <h1>About Us</h1>
        <p>Welcome to our newbie shop!</p>
        <div class="about-grid">
            <div class="about-box">
                <h3>Our Team</h3>
                <p>-------------------------------------</p>
                <p>Our team consists of two unexperienced developer and professional K-Popers
                    <br> Ode (230062)
                    <br> Maritza (230076)
                </p>
            </div>
            <div class="about-box">
                <h3>Our Mission</h3>
                <p>-------------------------------------</p>
                <p>We aim to provide a comfort and secure place for consumers through this website within an engaging interface </p>
            </div>
            <div class="about-box">
                <h3>Our Target</h3>
                <p>-------------------------------------</p>
                <p>K-ConcertKit is a website specializes in selling concert kits, targeting K-Popers as its primary consumers</p>
            </div>
        </div>
    </section>

    <section class="katalog-section container mt-5">
        <h3>Katalogue's</h3>
        <div class="row">
            <div class="col-md-3" style="width: 25%">
                <div class="catalog-box">
                    <a href="/katalog/bag">
                        <img src="/img/tas.png" alt="Katalog 1" class="catalog-img">
                        <div class="catalog-hover-text">Bag</div>
                    </a>
                </div>
            </div>
            <div class="col-md-3" style="width: 25%">
                <div class="catalog-box">
                    <a href="/katalog/lightstick">
                        <img src="/img/lightstick.jpg" alt="Katalog 2" class="catalog-img">
                        <div class="catalog-hover-text">Lightstick</div>
                    </a>
                </div>
            </div>
            <div class="col-md-3" style="width: 25%">
                <div class="catalog-box">
                    <a href="/katalog/electronic">
                        <img src="/img/elektronik.png" alt="Katalog 3" class="catalog-img">
                        <div class="catalog-hover-text">Electronic</div>
                    </a>
                </div>
            </div>
            <div class="col-md-3" style="width: 25%">
                <div class="catalog-box">
                    <a href="/katalog/more">
                        <img src="/img/More.jpg" alt="Katalog 3" class="catalog-img">
                        <div class="catalog-hover-text">More</div>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="footer-content">
        <h2><span>K</span>Concert<span>Kit</span></h2>
            <p>
                a special website to help you enjoy your concert better by providing 
                <br>
                high-quality goods in a secure and visually engaging place
            </p>
            <div class="footer-icons">
                <a href="https://maps.google.com/?cid=6050236566814697474&entry=gps"><i class="fas fa-map-marker-alt"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fas fa-envelope"></i></a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>Copyright &copy;2024 All rights reserved | <span>Created for educational purpose</span></p>
        </div>
    </footer>
    <script src="<?= base_url('script.js') ?>"></script>
</body>
</html>