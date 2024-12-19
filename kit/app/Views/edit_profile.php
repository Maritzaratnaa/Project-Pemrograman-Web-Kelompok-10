<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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

<section class="profile-section" id="profile">
    <div class="container">
        <div class="profile-header">
            <h1>Edit Profile</h1>
        </div>
        <form action="<?= site_url('profile/update') ?>" method="POST">
            <div class="form-group">
                <label for="nama_user">Username</label>
                <input type="text" id="nama_user" name="nama_user" class="form-control" value="<?= htmlspecialchars($user['nama_user']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="telepon">Phone</label>
                <input type="text" id="telepon" name="telepon" class="form-control" value="<?= htmlspecialchars($user['telepon']); ?>" required>
            </div>
            <div class="form-group">
                <label for="alamat">Address</label>
                <textarea id="alamat" name="alamat" class="form-control" required><?= htmlspecialchars($user['alamat']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-3" style="background-color: #F7CACA; color: black">Save Changes</button>
        </form>
        <div class="text-center mt-4" style="margin-bottom: 2rem">
            <a href="/profile" class="btn btn-secondary">Back to Profile</a>
        </div>
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
