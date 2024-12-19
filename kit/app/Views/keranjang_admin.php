<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Admin</title>
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
    <section class="daftar-beli">
        <div class="container mt-5">

            <h2  style="padding-top: 100px">Purchase List</h2>

            <?php if (session()->getFlashdata('message')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('message') ?>
                </div>
            <?php endif; ?>

            <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID User</th>
                    <th>Username</th>
                    <th>Purchase Date</th>
                    <th>Detail Products</th>
                    <th>Subtotal</th>
                    <th>Purchase Method</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pembelian as $item): ?>
                    <tr>
                        <td><?= $item['id_pembelian'] ?></td>
                        <td><?= $item['id_user'] ?></td>
                        <td><?= $item['nama_user'] ?></td>
                        <td><?= $item['tanggal_pembelian'] ?></td>
                        <td><?= $item['detail_barang'] ?></td>
                        <td><?= $item['total_harga'] ?></td>
                        <td><?= $item['metode_pembayaran'] ?></td>
                        <td><?= $item['status'] ?></td>
                        <td>
                            <form action="/keranjang_admin/updateStatus/<?= $item['id_pembelian'] ?>" method="post" class="d-inline">
                                <select name="status" class="form-status">
                                    <option <?= $item['status'] === 'Pending' ? 'selected' : '' ?> value="Pending">Pending</option>
                                    <option <?= $item['status'] === 'Processing' ? 'selected' : '' ?> value="Processing">Processing</option>
                                    <option <?= $item['status'] === 'Completed' ? 'selected' : '' ?> value="Completed">Completed</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                            </form>

                            <form action="/keranjang_admin/delete/<?= $item['id_pembelian'] ?>" method="post" class="d-inline">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pembelian ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
