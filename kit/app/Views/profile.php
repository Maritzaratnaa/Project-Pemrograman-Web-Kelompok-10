<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
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
        <div>
        <div class="profile-details">
            <div class="profile-header">
                <h1><span>User</span> Profile</h1>
            </div>
            <table>
                <tr>
                    <td><span>Username</span></td>
                    <td><span>:</span></td>
                    <td><?php echo htmlspecialchars($user['nama_user']); ?></td>
                </tr>
                <tr>
                    <td><span>Email</span></td>
                    <td><span>:</span></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                </tr>
                <tr>
                    <td><span>Phone</span></td>
                    <td><span>:</span></td>
                    <td><?php echo htmlspecialchars($user['telepon']); ?></td>
                </tr>
                <tr>
                    <td><span>Address</span></td>
                    <td><span>:</span></td>
                    <td><?php echo htmlspecialchars($user['alamat']); ?></td>
                </tr>
            </table>

            <br>

            <div class="text-center d-flex justify-content-center">
                <form action="<?= site_url('profile/edit') ?>" method="get" class="mr-2">
                    <button type="submit" class="btn btn-primary" style="background-color: #92A8D1">Edit</button>
                </form>

                <form action="<?= site_url('profile/delete/' . session()->get('id_user')) ?>" method="POST">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your account?')">Delete Account</button>
                </form>
            </div>

            <div class="logout-section text-center mt-4">
                <form action="<?= site_url('/logout') ?>" method="POST">
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>
        <div class="profile-daftar">
            <?php if ($user['role'] == '0'): ?>
                <a href="/detail_pembelian" class="btn btn-primary" style="background-color: #92A8D1">Your Purchase</a>
                <?php endif; ?>

                <?php if (!empty($users) && $user['role'] == '1'): ?>
                    <h2 class="mt-5">Daftar Pengguna</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Address</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><?= htmlspecialchars($u['id_user']); ?></td>
                                    <td><?= htmlspecialchars($u['nama_user']); ?></td>
                                    <td><?= htmlspecialchars($u['email']); ?></td>
                                    <td><?= htmlspecialchars($u['telepon']); ?></td>
                                    <td><?= htmlspecialchars($u['alamat']); ?></td>
                                    <td><?= htmlspecialchars($u['role']); ?></td>
                                    <td>
                                        <a href="<?= site_url('user/delete/' . $u['id_user']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <footer>
        <div class="footer-content">
        <h2 style="color:white;"><span>K</span>Concert<span>Kit</span></h2>
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