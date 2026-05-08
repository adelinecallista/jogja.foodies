<?php
include "koneksi.php";
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
//digunakan untuk mengambil data berdasarkan id dan jenis (makanan/minuman) yang akan diedit, lalu menampilkan form edit dengan data yang sudah terisi sesuai dengan data yang diambil dari database.
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$type = isset($_GET['type']) ? $_GET['type'] : 'makanan';
if ($type == 'makanan') {
    $table = 'makanan';
    $id_column = 'id_makanan';
    $nama_col = 'nama_makanan';
    $desk_col = 'deskripsi_makanan';
    $harga_col = 'harga_makanan';
    $lokasi_col = 'lokasi_makanan';
    $rating_col = 'rating_makanan';
} else {
    $table = 'minuman';
    $id_column = 'id_minuman';
    $nama_col = 'nama_minuman';
    $desk_col = 'deskripsi_minuman';
    $harga_col = 'harga_minuman';
    $lokasi_col = 'lokasi_minuman';
    $rating_col = 'rating_minuman';
}
$query = "SELECT * FROM $table WHERE $id_column = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
if (!$data) {
    echo "<h2>Data tidak ditemukan</h2>";
    echo "<a href='list.php'>Kembali ke Daftar Menu</a>";
    exit();
}