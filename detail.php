<?php
include "koneksi.php";

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
    echo "<a href='menu.html'>Kembali ke Menu</a>";
    exit();
}

// Mapping nama makanan ke nama file gambar asli
$file_mapping = [
    'Gudeg Yu Djum' => 'gudeg.jpeg',
    'Bakpia Pathok 25' => 'bakpia.jpeg',
    'Thiwul Ayu Mbok Sum' => 'thiwul.jpeg',
    'Cendol' => 'cendol.jpeg',
    'Ronde' => 'wedang.jpeg',
    'Wedang Uwuh' => 'WedangUwuh.jpeg'
];

// Ambil nama file dari mapping
$nama_file_gambar = isset($file_mapping[$data[$nama_col]]) 
    ? $file_mapping[$data[$nama_col]] 
    : 'default.jpeg';

// Cek apakah file benar-benar ada
if (!file_exists($nama_file_gambar)) {
    $nama_file_gambar = 'default.jpeg';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail <?= ucfirst($type) ?> - <?= $data[$nama_col] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
  <div class="container">
    <a class="navbar-brand" href="home.html">JOGJA.<span>FOODIES</span></a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" id="nav-home" href="home.html">HOME</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="nav-menu" href="menu.html">MENU</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="nav-login" href="login.php">LOGIN</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <img src="<?= $nama_file_gambar ?>" class="img-fluid rounded" alt="<?= $data[$nama_col] ?>" style="width:100%; object-fit:cover; max-height:400px;">
        </div>
        <div class="col-md-6">
            <h1 class="mb-3"><?= $data[$nama_col] ?></h1>
            <p><strong><i class="fas fa-map-marker-alt"></i> Lokasi:</strong> <?= $data[$lokasi_col] ?></p>
            <p><strong><i class="fas fa-tag"></i> Harga:</strong> Rp <?= number_format($data[$harga_col], 0, ',', '.') ?></p>
            <p>
                <strong><i class="bi bi-star-fill"></i> Rating:</strong>
                <?php for($i = 1; $i <= 5; $i++): ?>
                    <?php if($i <= $data[$rating_col]): ?>
                        <i class="bi bi-star-fill" style="color: #ffc107;"></i>
                    <?php else: ?>
                        <i class="bi bi-star" style="color: #ffc107;"></i>
                    <?php endif; ?>
                <?php endfor; ?>
                <span class="ms-1">(<?= $data[$rating_col] ?>.0)</span>
            </p>
            <p><strong>Deskripsi:</strong><br><?= nl2br($data[$desk_col]) ?></p>
            <a href="menu.html" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Kembali ke Menu</a>
        </div>
    </div>
</div>

<script>
  // Set active navbar berdasarkan URL saat ini
  const path = window.location.pathname;
  const page = path.split("/").pop();
  
  if (page === "home.html" || page === "") {
    document.getElementById("nav-home")?.classList.add("active");
  } else if (page === "menu.html") {
    document.getElementById("nav-menu")?.classList.add("active");
  } else if (page === "login.php") {
    document.getElementById("nav-login")?.classList.add("active");
  }
</script>

<footer class="text-center py-3 bg-dark text-white mt-5">
    <small>© 2025 Jogja.Foodies - All rights reserved</small>
</footer>

</body>
</html>