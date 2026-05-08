<?php
// Fungsi untuk mendapatkan koneksi database (sesuai modul)
function getKoneksi() {
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "jogja_foodies";
    
    // Membuat koneksi menggunakan MySQLi (sesuai modul)
    $konek = new mysqli($hostname, $username, $password, $database);
    
    // Cek koneksi (sesuai modul halaman 2)
    if ($konek->connect_error) {
        die('Maaf koneksi gagal: ' . $konek->connect_error);
    }
    
    // Set charset agar tidak error dengan karakter khusus
    $konek->set_charset("utf8");
    
    return $konek;
}

// Untuk penggunaan langsung di file (seperti di modul)
$konek = getKoneksi();
?>