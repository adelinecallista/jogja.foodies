<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
echo "<h2>Selamat datang, Admin!</h2>";
echo "<a href='logout.php'>Logout</a>";
// di sini nanti kamu tambahkan CRUD (tambah, edit, hapus rekomendasi makanan)
?>
