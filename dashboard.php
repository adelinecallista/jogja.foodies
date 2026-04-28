<?php
    session_start();
    if (!isset($_SESSION['admin'])) {
        header("Location: login.html");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Dashboard Admin JogjaFoodies</h2>
    <p>Selamat datang, <?php echo $_SESSION['admin']; ?>!</p>

<ul>
    <li><a href="tambah_menu.php">Tambah List Rekomendasi</a></li>
    <li><a href="list.php">Lihat & Edit Daftar Makanan</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>
</body>
</html>