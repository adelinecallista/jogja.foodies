<?php
include "koneksi.php";

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data dari tabel menu
$query = "SELECT * FROM menu WHERE id_menu = $id";
$result = mysqli_query($conn, $query);

// Cek query
if (!$result) {
    die("Query error: " . mysqli_error($conn));
}

// Ambil data
$data = mysqli_fetch_assoc($result);

// Jika tidak ada data
if (!$data) {
    echo "<h2>Data tidak ditemukan</h2>";
    echo "<a href='menu.php'>Kembali ke Menu</a>";
    exit();
}

// Mapping gambar
$file_mapping = [
    'Gudeg Yu Djum' => 'gudeg.jpeg',
    'Bakpia Pathok 25' => 'bakpia.jpeg',
    'Thiwul Ayu Mbok Sum' => 'thiwul.jpeg',
    'Cendol' => 'cendol.jpeg',
    'Ronde' => 'wedang.jpeg',
    'Wedang Uwuh' => 'wedanguwuh.jpeg'
];

// Ambil nama file gambar
$nama_file_gambar = isset($file_mapping[$data['nama_menu']]) 
    ? $file_mapping[$data['nama_menu']] 
    : 'default.jpeg';

// Arahkan ke folder img
$nama_file_gambar = "img/" . $nama_file_gambar;

// Cek file ada atau tidak
if (!file_exists($nama_file_gambar)) {
    $nama_file_gambar = "img/default.jpeg";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail - <?= $data['nama_menu'] ?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <div class="container">
    <a class="navbar-brand" href="home.html">JOGJA.<span style="color:orange;">FOODIES</span></a>
    <div>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="home.html">HOME</a></li>
        <li class="nav-item"><a class="nav-link active" href="menu.php">MENU</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php">LOGIN</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- CONTENT -->
<div class="container my-5">
    <div class="row">

        <!-- GAMBAR -->
        <div class="col-md-6">
            <img src="<?= $nama_file_gambar ?>" 
                 class="img-fluid rounded shadow"
                 style="width:100%; max-height:400px; object-fit:cover;">
        </div>

        <!-- DETAIL -->
        <div class="col-md-6">
            <h1 class="mb-3"><?= $data['nama_menu'] ?></h1>

            <p>
                <strong><i class="fas fa-map-marker-alt"></i> Lokasi:</strong><br>
                <?= $data['lokasi'] ?>
            </p>

            <p>
                <strong><i class="fas fa-tag"></i> Harga:</strong><br>
                Rp <?= number_format($data['harga'], 0, ',', '.') ?>
            </p>

            <p>
                <strong><i class="bi bi-star-fill"></i> Rating:</strong><br>

                <?php for($i = 1; $i <= 5; $i++): ?>
                    <?php if($i <= $data['rating']): ?>
                        <i class="bi bi-star-fill text-warning"></i>
                    <?php else: ?>
                        <i class="bi bi-star text-warning"></i>
                    <?php endif; ?>
                <?php endfor; ?>

                <span>(<?= $data['rating'] ?>.0)</span>
            </p>

            <p>
                <strong>Deskripsi:</strong><br>
                <?= nl2br($data['deskripsi']) ?>
            </p>

            <!-- BUTTON -->
            <a href="menu.html" class="btn btn-secondary mt-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <a href="order.php?id=<?= $data['id_menu'] ?>" class="btn btn-success mt-3">
                <i class="fas fa-shopping-cart"></i> Pesan
            </a>

        </div>
    </div>
</div>

<!-- FOOTER -->
<footer class="text-center py-3 bg-dark text-white mt-5">
    <small>© 2025 Jogja.Foodies</small>
</footer>

</body>
</html>