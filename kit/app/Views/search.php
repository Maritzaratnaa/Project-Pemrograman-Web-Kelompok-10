<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="<?php echo base_url('styles.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<header>
        <nav class="navbar">
            <div class="logo">
                <a href="home">K<span>Concert</span>Kit</a>
            </div>
            <div class="search-bar">
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
    <div class="container mt-5" style="padding: 100px">
        <h3>Search Results for: "<?= esc($query) ?>"</h3>

        <?php if (empty($barang)): ?>
            <p>No products found.</p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($barang as $barang): ?>
                    <div class="col-md-3">
                        <div class="catalog-box">
                            <img src="<?= base_url(esc($barang['gambar'])) ?>" 
                                 alt="<?= esc($barang['nama_barang']); ?>" 
                                 class="catalog-img"
                                 onerror="this.onerror=null;this.src='<?= base_url('img/default.jpg') ?>';">
                            <div class="catalog-footer">
                                <div class="catalog-name"><?= esc($barang['nama_barang']); ?></div>
                                <div class="catalog-price">Rp<?= number_format($barang['harga'], 0, ',', '.'); ?></div>
                                <?php if (session()->get('role') != '1'): ?>
                                    <a href="<?= site_url('keranjang/add/' . session()->get('id_user') . '/' . $barang['id_barang']) ?>" 
                                        class="cart-icon-btn"><i class="fas fa-shopping-bag"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <footer>
        <div class="footer-bottom">
            <p>Copyright &copy;2024 All rights reserved | <span>Created for educational purpose</span></p>
        </div>
    </footer>
    <script src="<?= base_url('script.js') ?>"></script>
</body>
</html>