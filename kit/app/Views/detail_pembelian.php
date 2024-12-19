<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembelian Anda</title>
    <link rel="stylesheet" href="<?php echo base_url('styles.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
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
    <section class="daftar-beli" style="padding-top: 110px;">
        <div>
            <h2>Your Purchase List</h2>

            <?php if (session()->getFlashdata('message')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('message') ?>
                </div>
            <?php endif; ?>

            <?php if (empty($pembelian)): ?>
                <div class="alert alert-warning" style="background-color: #F7CACA">
                    You have not made any purchases yet.
                </div>
            <?php else: ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Purchase Date</th>
                            <th>Detail Products</th>
                            <th>Total Products</th>
                            <th>Subtotal</th>
                            <th>Purchase Method</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pembelian as $item): ?>
                            <tr>
                                <td><?= $item['tanggal_pembelian'] ?></td>
                                <td><?= $item['detail_barang'] ?></td>
                                <td><?= $item['total_barang'] ?></td>
                                <td><?= $item['total_harga'] ?></td>
                                <td><?= $item['metode_pembayaran'] ?></td>
                                <td><?= $item['status'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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