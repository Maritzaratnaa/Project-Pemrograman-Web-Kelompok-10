<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="<?php echo base_url('styles.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body style="background-color: #92A8D1;">
    <header>
        <nav class="navbar">
            <div class="logo">
                <a href="/home">K<span>Concert</span>Kit</a>
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
        <div class="rent-cart-wrapper">
            <h2><span>My</span> Cart</h2>
        </div>
    </header>

    <section class="rent-cart" style="padding-bottom: 7rem">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success'); ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error'); ?>
            </div>
        <?php endif; ?>

        <div class="cart-items">
            <div class="loading" style="display: none;"></div>
            <?php if (!empty($keranjang)): ?>
                <?php foreach ($keranjang as $item): ?>
                    <div class="cart-item">
                        <div class="item-details">
                            <img src="<?= base_url($item['gambar']) ?>" alt="gambar_barang" width="100">
                            <div class="item-info">
                                <h5><?= $item['nama_barang']; ?></h5>
                                <div class="item-quantity">
                                    <button class="btn-quantity" data-action="decrease" data-id="<?= $item['id_detail_keranjang']; ?>" data-stock="<?= $item['quantity']; ?>">-</button>
                                    <span class="quantity"><?= number_format($item['jumlah']); ?></span>
                                    <button class="btn-quantity" data-action="increase" data-id="<?= $item['id_detail_keranjang']; ?>" data-stock="<?= $item['quantity']; ?>">+</button>
                                </div>
                            </div>
                        </div>
                        <p class="item-price-text">
                            <span class="item-quantity-text"><?= number_format($item['jumlah']); ?> x</span> 
                            Rp <?= number_format($item['harga'], 0, ',', '.'); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Your shopping cart is empty. Start adding items from your favorite group now!</p>
            <?php endif; ?>
        </div>

        <div class="order-summary">
            <h4>Order Summary</h4>
            <?php if (!empty($keranjang)): ?>
                <?php foreach ($keranjang as $item): ?>
                    <div class="order-summary-items" data-id="<?= $item['id_detail_keranjang']; ?>">
                        <span style="text-align: left"><?= $item['nama_barang']; ?></span>
                        <span class="item-price" style="text-align: right">Rp <?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.'); ?></span>
                    </div>

                <?php endforeach; ?>
                    <p class="total-price">Total: Rp <?= number_format($totalHarga, 0, ',', '.'); ?></p>
                    <form action="<?= site_url('payment/' . $id_user) ?>" method="GET">
                    <button type="submit">Continue to Payment</button>
            <?php else: ?>
                <p>No order summary available yet.</p>
            <?php endif; ?>
        </div>
    </section>
    <footer>
        <div class="footer-bottom">
            <p>Copyright &copy;2024 All rights reserved | <span>Created for educational purpose</span></p>
        </div>
    </footer>
    <script src="<?= base_url('script.js') ?>"></script>
</body>
</html>