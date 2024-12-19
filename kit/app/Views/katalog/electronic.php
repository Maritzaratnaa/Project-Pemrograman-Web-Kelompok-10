<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electronic</title>
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
    </header>
    <section class="catalog-title">
        <h3><span>Electronic</span> Product</h3>
    </section>
    <section class="katalog-section container mt-5">
        <div class="row">
            <?php
                if (!empty($items)) {
                    foreach ($items as $item) {
                        echo '
                    <div class="col-md-3">
                        <div class="catalog-box">
                            <img src="' . base_url($item['gambar']) . '" alt="' . $item['nama_barang'] . '" class="catalog-img">
                            <div class="catalog-footer">
                                <div>
                                    <p class="catalog-desc">' . $item['nama_barang'] . '</p>
                                    <b class="catalog-price">Rp. ' . number_format($item['harga'], 0, ',', '.') . '</b>
                                </div>';

                        if (session()->get('role') == '1') {
                            echo '
                                <div>
                                    <a href="' . site_url('barang/edit/' . $item['id_barang']) . '" class="edit-btn">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="' . site_url('barang/delete/' . $item['id_barang']) . '" class="delete-btn" onclick="return confirm(\'Apakah Anda yakin ingin menghapus barang ini?\')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>';
                        } else {
                            echo '
                                <a href="' . site_url('keranjang/add/' . session()->get('id_user') . '/' . $item['id_barang']) . '" class="cart-icon-btn">
                                    <i class="fas fa-shopping-bag"></i>
                                </a>';
                        }

                        echo '
                            </div>
                        </div>
                    </div>';
                    }
                } else {
                    echo '<p>Tidak ada gambar yang ditemukan.</p>';
                }
            ?>
        </div>

        <?php if (session()->get('role') == '1'): ?>
            <div class="mt-4">
            <a href="<?= site_url('barang/add/' . $kategori) ?>" class="btn btn-primary" style="margin-bottom: 50px; background-color: #F7CACA; color: black">Tambah Barang</a>
            </div>
        <?php endif; ?>
    </section>
    <footer>
        <div class="footer-bottom">
            <p>Copyright &copy;2024 All rights reserved | <span>Created for educational purpose</span></p>
        </div>
    </footer>
    <script src="<?= base_url('script.js') ?>"></script>
</body>
</html>
