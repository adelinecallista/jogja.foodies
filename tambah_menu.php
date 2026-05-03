<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit();
}
include "koneksi.php";

if (isset($_POST['simpan'])) {
    $type = $_POST['type']; // makanan atau minuman
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $foto = $_POST['foto'];
    $lokasi = $_POST['lokasi'];
    $rating = $_POST['rating'];

    $query = "INSERT INTO $type (nama, deskripsi, harga, foto, lokasi, rating) 
              VALUES ('$nama','$deskripsi','$harga','$foto','$lokasi','$rating')";
    mysqli_query($conn, $query);
    header("Location: list.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Item</title>
</head>
<body>
    <h2>Tambah Item Baru</h2>
    <form method="POST">
        Jenis: 
        <select name="type">
            <option value="makanan">Makanan</option>
            <option value="minuman">Minuman</option>
        </select><br><br>

        Nama: <input type="text" name="nama" required><br><br>
        Deskripsi: <textarea name="deskripsi" required></textarea><br><br>
        Harga: <input type="number" name="harga" required><br><br>
        Foto (nama file): <input type="text" name="foto"><br><br>
        Lokasi: <input type="text" name="lokasi"><br><br>
        Rating: <input type="number" step="0.1" name="rating" min="0" max="5"><br><br>

        <button type="submit" name="simpan">Simpan</button>
    </form>
    <a href="dashboard.php">Kembali ke Dashboard</a>
</body>
</html>
