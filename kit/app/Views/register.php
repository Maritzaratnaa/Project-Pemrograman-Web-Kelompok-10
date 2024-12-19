<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="<?php echo base_url('styles.css'); ?>">
</head>
<body style="background-color: white;">
    <div class="register-content">
        <h3>Join Us for The Good Experience</h3>
        <h1>K<span>Concert</span><b>Kit</b></h1>
        
        <section class="login-container">

        <form id="registerForm" action="<?php echo base_url('/register/submit'); ?>" method="post">
            <?= csrf_field(); ?>

            <label for="nama_user">Username</label>
            <input type="text" name="nama_user" id="nama_user" placeholder="Enter Username" value="<?= old('nama_user'); ?>" required>
            <?php if (session('errors.nama_user')): ?>
                <span class="error"><?= session('errors.nama_user'); ?></span>
            <?php endif; ?>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Enter Email" value="<?= old('email'); ?>" required>
            <?php if (session('errors.email')): ?>
                <span class="error"><?= session('errors.email'); ?></span>
            <?php endif; ?>

            <label for="telepon">Phone</label>
            <input type="tel" name="telepon" id="telepon" placeholder="Enter Phone Number" value="<?= old('telepon'); ?>" required>
            <?php if (session('errors.telepon')): ?>
                <span class="error"><?= session('errors.telepon'); ?></span>
            <?php endif; ?>

            <label for="alamat">Address</label>
            <textarea name="alamat" id="alamat" placeholder="Enter Address" required><?= old('alamat'); ?></textarea>
            <?php if (session('errors.alamat')): ?>
                <span class="error"><?= session('errors.alamat'); ?></span>
            <?php endif; ?>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter Password" required>
            <?php if (session('errors.password')): ?>
                <span class="error"><?= session('errors.password'); ?></span>
            <?php endif; ?>

            <button type="submit" class="btn">Register</button>
        </form>
        </section>
    </div>
    
    <script src="script.js"></script>
</body>
</html>