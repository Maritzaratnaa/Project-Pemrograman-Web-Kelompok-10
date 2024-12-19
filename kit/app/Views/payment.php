<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
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
            <h2>Payment</h2>
        </div>
    </header>
    <section class="order-summary-payment" >
        <form action="<?= site_url('checkout/' . $id_user); ?>" method="POST">
            <table>
                <tr>
                    <td><strong>Nama</strong></td>
                    <td class="text-right"><?= $user['nama_user']; ?></td> 
                </tr>
                <tr>
                    <td><strong>Alamat</strong></td>
                    <td class="text-right"><?= $user['alamat']; ?></td> 
                </tr>

                <?php foreach ($keranjang as $item): ?>
                    <tr>
                        <td><?= $item['nama_barang']; ?></td>
                        <td class="text-right"><?= $item['jumlah']; ?> x Rp <?= number_format($item['harga'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>

                <tr class="total">
                    <td><strong>Total</strong></td>
                    <td class="text-right"><strong>Rp <?= number_format($totalHarga, 0, ',', '.'); ?></strong></td>
                </tr>
                <tr>
                    <td><label for="payment-method"><strong>Metode Pembayaran</strong></label></td>
                    <td class="text-right">
                        <select id="payment-method" name="payment-method" required>
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="Gopay">Gopay</option>
                            <option value="OVO">OVO</option>
                            <option value="Kartu Kredit">Kartu Kredit</option>
                        </select>
                    </td>
                </tr>
            </table>
            <button type="submit" class="checkout-btn">Checkout</button>
        </form>
    </section>
    <footer>
        <div class="footer-bottom">
            <p>Copyright &copy;2024 All rights reserved | <span>Created for educational purpose</span></p>
        </div>
    </footer>
    <script src="<?= base_url('script.js') ?>"></script>
</body>
</html>
