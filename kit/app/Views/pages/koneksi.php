<?php
    $koneksi = mysqli_connect("localhost", "root", "", "k_concertkit");
    if(!$koneksi){
        die("Koneksi gagal!" . mysqli_connect_error());
    }
?>