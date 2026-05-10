<?php
//menyesuaikan dengan database yang digunakan
function getKoneksi() {
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "jogja_foodies";
    
    // Membuat koneksi
    $konek = new mysqli($hostname, $username, $password, $database);
    
    // Cek koneksi
    if ($konek->connect_error) {
        die('Maaf koneksi gagal: ' . $konek->connect_error);
    }
    
    // untuk memastikan data yang masuk ke database menggunakan UTF-8
    $konek->set_charset("utf8");
    
    return $konek;
}

// membuat koneksi ke database
$konek = getKoneksi();
?>