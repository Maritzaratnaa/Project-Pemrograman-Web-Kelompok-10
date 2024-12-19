<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="<?php echo base_url('styles.css'); ?>">
</head>
<body style="background-color: white;">
    <div class="login-content">
        <h3>Enjoy your every concert with</h3>
        <h1>K<span>Concert</span><b>Kit</b></h1>

        <section class="login-container">
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?php echo session()->getFlashdata('error'); ?>
                </div>
            <?php endif; ?>

            <form id="loginForm" action="<?php echo base_url('login/submit'); ?>" method="post">
                
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter Email" required>
                
                
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Enter Password" required>
                
                <input type="submit" name="login" value="Login" style="margin-top:10px;">
                <p style="margin-top:10px;">Don't have an account? <a href="/register">Register here</a></p>        
            </form>
        </section>
    </div>
</body>
</html>