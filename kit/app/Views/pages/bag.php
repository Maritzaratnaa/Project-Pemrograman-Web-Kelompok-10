<?php
    include 'koneksi.php';

    $id_katalog_lightstick = 2;

    $query = "
        SELECT b.nama_barang, b.harga, b.gambar 
        FROM barang b
        JOIN katalog k ON b.id_katalog = k.id_katalog 
        WHERE k.id_katalog = $id_katalog_lightstick
    ";

    $result = $koneksi->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bag Catalogue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <nav class="navbar">
        <div class="logo">
                <a href="home.php">K<span>Concert</span>Kit</a>
            </div>

            <div class="search-bar">
                <input type="text" placeholder="Search products...">
                <button type="submit"><i class="fas fa-search"></i></button>
            </div>

            <div class="nav-icons">
                <a href="cart.php" class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count">3</span>
                </a>
                <a href="login.php" class="profile-icon">
                    <i class="fas fa-user"></i>
                </a>
            </div>
        </nav>
    </header>

    <section class="katalog-section container mt-5">
        <h3>Bag Catalogue</h3>
        <div class="row">
            <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $gambar_base64 = base64_encode($row['gambar']);
                        echo '
                        <div class="col-md-3">
                            <div class="catalog-box">
                                <img src="data:image/jpeg;base64,' . $gambar_base64 . '" alt="' . $row['nama_barang'] . '" class="catalog-img">
                                <a class="catalog-desc">' . $row['nama_barang'] . '</a><br>
                                <b class="catalog-desc">Rp. ' . number_format($row['harga'], 0, ',', '.') . '</b>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<p>Tidak ada gambar yang ditemukan.</p>';
                }

                $koneksi->close();
            ?>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
