<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
include "koneksi.php";

// Ambil data makanan
$query_makanan = "SELECT id_makanan as id, nama_makanan as nama, 'makanan' as jenis FROM makanan";
$query_minuman = "SELECT id_minuman as id, nama_minuman as nama, 'minuman' as jenis FROM minuman";
$result_makanan = mysqli_query($conn, $query_makanan);
$result_minuman = mysqli_query($conn, $query_minuman);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container mt-5">
    <h2>Daftar Semua Menu</h2>
    <a href="tambah_menu.php" class="btn btn-success mb-3">+ Tambah Baru</a>
    <a href="dashboard.php" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>

    <h3>🍽️ Makanan</h3>
    <table class="table table-bordered">
        <tr><th>ID</th><th>Nama</th><th>Aksi</th></tr>
        <?php while($row = mysqli_fetch_assoc($result_makanan)): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['nama'] ?></td>
            <td>
                <a href="edit.php?id=<?= $row['id'] ?>&type=makanan" class="btn btn-warning btn-sm">Edit</a>
                <a href="hapus.php?id=<?= $row['id'] ?>&type=makanan" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h3>🥤 Minuman</h3>
    <table class="table table-bordered">
        <tr><th>ID</th><th>Nama</th><th>Aksi</th></tr>
        <?php while($row = mysqli_fetch_assoc($result_minuman)): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['nama'] ?></td>
            <td>
                <a href="edit.php?id=<?= $row['id'] ?>&type=minuman" class="btn btn-warning btn-sm">Edit</a>
                <a href="hapus.php?id=<?= $row['id'] ?>&type=minuman" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>