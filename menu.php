<?php
// file: menu.php
session_start();
require_once 'config/koneksi.php';

$current_page = 'menu.php';

// Get filter parameters
$category = isset($_GET['category']) && $_GET['category'] !== 'all' 
    ? mysqli_real_escape_string($konek, $_GET['category']) 
    : '';
$search = isset($_GET['search']) 
    ? mysqli_real_escape_string($konek, $_GET['search']) 
    : '';

// Build query
$query = "SELECT * FROM foods WHERE 1=1";

if ($category) {
    $query .= " AND category = '$category'";
}
if ($search) {
    $query .= " AND (name LIKE '%$search%' OR description LIKE '%$search%' OR location LIKE '%$search%')";
}
$query .= " ORDER BY rating DESC, name ASC";

$result = mysqli_query($konek, $query);
$foods = [];
while ($row = mysqli_fetch_assoc($result)) {
    $foods[] = $row;
}

// Get unique categories
$cat_query = "SELECT DISTINCT category FROM foods ORDER BY category";
$cat_result = mysqli_query($konek, $cat_query);
$categories = [];
while ($row = mysqli_fetch_assoc($cat_result)) {
    $categories[] = $row['category'];
}

// Fungsi gambar
function getFoodImage($food_name) {
    $clean_name = strtolower(str_replace(' ', '', $food_name));
    
    $images = [
        'gudeg' => 'assets/gudeg.jpeg',
        'bakpia' => 'assets/bakpia.jpeg',
        'cendol' => 'assets/cendol.jpeg',
        'thiwul' => 'assets/thiwul.jpeg',
        'ronde' => 'assets/ronde.jpeg',
        'sate' => 'assets/sate.jpg',
        'ayam' => 'assets/ayam.jpeg',
    ];
    
    foreach ($images as $keyword => $path) {
        if (strpos($clean_name, $keyword) !== false) {
            return $path;
        }
    }
    
    return 'assets/WedangUwuh.jpeg';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Kuliner - Jogja Foodies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #FFF5F7;
            color: #4A4A4A;
        }

        :root {
            --pastel-pink: #FFB7C5;
            --pastel-pink-dark: #F5A3B0;
            --pastel-mauve: #E8D1E0;
            --pastel-blush: #FFD1DC;
            --pastel-rose: #F7CAC9;
            --pastel-lavender: #E6E6FA;
            --text-soft: #5A5A6E;
            --white-soft: #FFF9FB;
        }

        .navbar {
            background: #FFFFFF;
            box-shadow: 0 2px 20px rgba(0,0,0,0.04);
            padding: 0.8rem 0;
        }

        .navbar-brand {
            font-size: 1.6rem;
            font-weight: 800;
            color: #F5A3B0 !important;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .navbar-brand img {
            height: 45px;
            width: auto;
            border-radius: 12px;
            object-fit: cover;
        }

        .nav-link {
            color: #5A5A6E !important;
            font-weight: 500;
            margin: 0 1rem;
            transition: all 0.3s;
        }

        .nav-link:hover {
            color: #F5A3B0 !important;
        }

        .nav-link.active-red {
            color: #F5A3B0 !important;
            font-weight: 600;
            position: relative;
        }

        .nav-link.active-red::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 3px;
            background: #F5A3B0;
            border-radius: 3px;
        }

        .btn-primary-custom {
            background: #F5A3B0;
            border: none;
            padding: 0.5rem 1.8rem;
            border-radius: 50px;
            font-weight: 600;
            color: white;
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            background: #E8919F;
            transform: translateY(-2px);
            color: white;
        }

        .btn-order {
            display: inline-block;
            background: #F5A3B0;
            border: none;
            border-radius: 12px;
            padding: 0.6rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
            width: 100%;
            text-align: center;
            text-decoration: none;
            font-size: 0.85rem;
        }

        .btn-order:hover {
            background: #E8919F;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(245,163,176,0.3);
            color: white;
        }

        .page-header {
            background: #FFD1DC;
            padding: 80px 0 60px;
            color: #6B4E5E;
            text-align: center;
        }

        .page-header h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            color: #6B4E5E;
        }

        .page-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            color: #7A5C68;
        }

        .filter-section {
            background: white;
            padding: 1.5rem 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            position: sticky;
            top: 76px;
            z-index: 99;
        }

        .filter-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
        }

        .filter-btn {
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            background: #FFF9FB;
            color: #8A7A82;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            border: 2px solid #FFE2E8;
            font-size: 0.9rem;
        }

        .filter-btn:hover {
            background: #F5A3B0;
            color: white;
            border-color: #F5A3B0;
        }

        .filter-btn.active {
            background: #F5A3B0;
            color: white;
            border-color: #F5A3B0;
            box-shadow: 0 4px 12px rgba(245,163,176,0.3);
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            border-radius: 50px;
            padding: 0.7rem 1.2rem;
            border: 2px solid #FFE2E8;
            background: #FFF9FB;
            font-size: 0.9rem;
            width: 100%;
        }

        .search-box input:focus {
            border-color: #F5A3B0;
            outline: none;
        }

        .search-box button {
            position: absolute;
            right: 5px;
            top: 5px;
            background: #F5A3B0;
            border: none;
            border-radius: 50px;
            padding: 0.4rem 1.2rem;
            color: white;
            font-weight: 500;
        }

        .search-box button:hover {
            background: #E8919F;
        }

        .result-info {
            margin: 1.5rem 0;
            padding: 0.5rem 0;
            border-bottom: 2px solid #FFE2E8;
        }

        .result-info span {
            color: #F5A3B0;
            font-weight: 700;
        }

        .food-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s;
            height: 100%;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            border: 1px solid #FFE2E8;
        }

        .food-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(245,163,176,0.15);
        }

        .card-img-wrapper {
            position: relative;
            overflow: hidden;
            height: 220px;
        }

        .card-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .food-card:hover .card-img-wrapper img {
            transform: scale(1.1);
        }

        .category-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(90,74,79,0.8);
            backdrop-filter: blur(5px);
            color: white;
            padding: 0.3rem 1rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .rating {
            color: #F4C2C2;
            margin: 0.5rem 0;
            font-size: 0.9rem;
        }

        .rating span {
            color: #A58E98;
            margin-left: 0.5rem;
            font-size: 0.8rem;
        }

        .price {
            font-size: 1.2rem;
            font-weight: 700;
            color: #F5A3B0;
        }

        .location {
            color: #A58E98;
            font-size: 0.8rem;
            margin: 0.5rem 0;
        }

        .location i {
            color: #F5A3B0;
            margin-right: 0.3rem;
        }

        .food-description {
            color: #8A7A82;
            font-size: 0.85rem;
            line-height: 1.4;
            margin: 0.5rem 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .empty-state {
            text-align: center;
            padding: 4rem;
            background: #FFF9FB;
            border-radius: 20px;
            border: 1px solid #FFE2E8;
        }

        .empty-state i {
            font-size: 4rem;
            color: #FFD1DC;
            margin-bottom: 1rem;
        }

        footer {
            background: #F8E9EE;
            color: #7A5C68;
            padding: 4rem 0 2rem;
            margin-top: 4rem;
            border-top: 1px solid #FFE2E8;
        }

        .footer-brand {
            font-size: 1.8rem;
            font-weight: 800;
            color: #F5A3B0;
            margin-bottom: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .footer-brand img {
            height: 40px;
            width: auto;
            border-radius: 10px;
        }

        footer h6 {
            color: #6B4E5E;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        footer a {
            color: #8A6F7A;
            text-decoration: none;
        }

        footer a:hover {
            color: #F5A3B0;
        }

        .social-icons a {
            display: inline-block;
            width: 35px;
            height: 35px;
            line-height: 35px;
            text-align: center;
            background: #FFE2E8;
            border-radius: 50%;
            margin-right: 0.5rem;
            transition: all 0.3s;
            color: #F5A3B0;
        }

        .social-icons a:hover {
            background: #F5A3B0;
            color: white;
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }
            
            .filter-group {
                justify-content: center;
                margin-bottom: 1rem;
            }
            
            .filter-btn {
                padding: 0.4rem 1rem;
                font-size: 0.8rem;
            }

            .navbar-brand img {
                height: 35px;
            }

            .footer-brand img {
                height: 32px;
            }
        }
    </style>
</head>
<body>

<!-- ========== NAVBAR ========== -->
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="assets/logo.jpeg" alt="Jogja Foodies Logo">
            <span class="fw-bold fs-4" style="color: #F5A3B0;">Jogja Foodies</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold active-red" href="menu.php">Menu</a>
                </li>
            </ul>
        </div>
        
        <div class="d-flex align-items-center" style="gap: 10px;">
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="dropdown d-inline-block">
                    <button class="btn rounded-pill px-3 dropdown-toggle" type="button" data-bs-toggle="dropdown" 
                            style="background: #F5A3B0; color: white; border: none;">
                        <i class="fas fa-user-circle"></i> 
                        <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']) ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="my_orders.php">My Orders</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn rounded-pill px-3 me-2 fw-bold" style="background: #F5A3B0; color: white; border: none;">Masuk</a>
                <a href="register.php" class="btn rounded-pill px-3 fw-bold" style="border: 2px solid #F5A3B0; color: #F5A3B0;">Daftar</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- ========== PAGE HEADER ========== -->
<section class="page-header">
    <div class="container">
        <h1>Menu Kuliner Jogja</h1>
        <p>Temukan berbagai makanan khas Yogyakarta yang menggugah selera</p>
    </div>
</section>

<!-- ========== FILTER SECTION ========== -->
<section class="filter-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="filter-group">
                    <a href="?category=all" class="filter-btn <?= (!isset($_GET['category']) || $_GET['category'] == 'all') ? 'active' : '' ?>">Semua</a>
                    <?php foreach($categories as $cat): ?>
                        <a href="?category=<?= urlencode($cat) ?>" 
                           class="filter-btn <?= ($category == $cat) ? 'active' : '' ?>">
                            <?= htmlspecialchars($cat) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-lg-4">
                <form method="GET" action="">
                    <?php if($category): ?>
                        <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
                    <?php endif; ?>
                    <div class="search-box">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari makanan..." value="<?= htmlspecialchars($search) ?>">
                        <button type="submit">Cari</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- ========== MENU GRID ========== -->
<section class="container my-5">
    <div class="result-info">
        <p>Menampilkan <span><?= count($foods) ?></span> menu kuliner</p>
    </div>

    <div class="row g-4">
        <?php if(count($foods) > 0): ?>
            <?php foreach($foods as $food): ?>
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="food-card">
                        <div class="card-img-wrapper">
                            <img src="<?= getFoodImage($food['name']) ?>" alt="<?= htmlspecialchars($food['name']) ?>">
                            <span class="category-badge"><?= htmlspecialchars($food['category']) ?></span>
                        </div>
                        <div class="p-4">
                            <h5 class="fw-bold mb-2" style="color: #6B4E5E;"><?= htmlspecialchars($food['name']) ?></h5>
                            
                            <div class="rating">
                                <?php 
                                $full = floor($food['rating']);
                                $half = ($food['rating'] - $full) >= 0.5;
                                for($i = 1; $i <= 5; $i++): ?>
                                    <?php if($i <= $full): ?>
                                        <i class="fas fa-star"></i>
                                    <?php elseif($half && $i == $full + 1): ?>
                                        <i class="fas fa-star-half-alt"></i>
                                    <?php else: ?>
                                        <i class="far fa-star"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                <span>(<?= number_format($food['rating'], 1) ?>)</span>
                            </div>
                            
                            <div class="location">
                                <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($food['location']) ?>
                            </div>
                            
                            <div class="food-description">
                                <?= htmlspecialchars(substr($food['description'] ?? '', 0, 70)) ?>...
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="price">Rp <?= number_format($food['price'], 0, ',', '.') ?></span>
                                <a href="order.php?id=<?= $food['id'] ?>" class="btn-order">Pesan</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <h4 style="color: #6B4E5E;">Tidak ada menu ditemukan</h4>
                    <p class="text-muted">Coba cari dengan kata kunci lain atau reset filter</p>
                    <a href="menu.php" class="btn btn-primary-custom mt-3">Reset Filter</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>


<!-- Footer dengan Logo Gambar juga -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="footer-brand">
                    <img src="assets/logo.jpeg" alt="Jogja Foodies Logo">
                    Jogja Foodies
                </div>
                <p>Temukan makanan favoritmu!</p>
            </div>
            <div class="col-md-3 mb-4">
                <h6>Contact Info</h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-map-marker-alt"></i> Yogyakarta, Indonesia</li>
                    <li><i class="fas fa-envelope"></i> jogjafoodies@gmail.com</li>
                    <li><i class="fas fa-phone"></i> +62 812 3456 7890</li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h6>Jam Operasional</h6>
                <ul class="list-unstyled">
                    <li>Senin - Jumat: 09:00 - 21:00</li>
                    <li>Sabtu - Minggu: 08:00 - 22:00</li>
                </ul>
            </div>
        </div>
        <hr class="my-4" style="border-color: rgba(245,163,176,0.2);">
        <div class="text-center">
            <p class="mb-0">&copy; 2026 Jogja Foodies</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>