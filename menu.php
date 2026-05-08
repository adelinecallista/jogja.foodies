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
        'wedang' => 'assets/wedang.jpeg',
    ];
    
    foreach ($images as $keyword => $path) {
        if (strpos($clean_name, $keyword) !== false) {
            return $path;
        }
    }
    
    return 'assets/gudeg.jpeg';
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
    <style>
        /* ========== RESET & BASE ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            color: #2d3436;
        }

        /* ========== NAVBAR ========== */
        .navbar {
            background: #ffffff;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 800;
            color: #FF6B35 !important;
        }

        .navbar-brand i {
            color: #FF6B35;
        }

        .nav-link {
            color: #2d3436 !important;
            font-weight: 500;
            margin: 0 1rem;
            transition: all 0.3s;
        }

        .nav-link:hover {
            color: #FF6B35 !important;
        }

        .nav-link.active-red {
            color: #FF6B35 !important;
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
            background: #FF6B35;
            border-radius: 3px;
        }

        /* ========== BUTTONS ========== */
        .btn-primary-custom {
            background: #FF6B35;
            border: none;
            padding: 0.5rem 1.8rem;
            border-radius: 50px;
            font-weight: 600;
            color: white;
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            background: #e55a2b;
            transform: translateY(-2px);
            color: white;
        }

        .btn-order {
            display: inline-block;
            background: linear-gradient(135deg, #FF6B35, #FF8C42);
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
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,107,53,0.3);
            color: white;
        }

        /* ========== PAGE HEADER ========== */
        .page-header {
            background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 50%, #FFD166 100%);
            padding: 80px 0 60px;
            color: white;
            text-align: center;
        }

        .page-header h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .page-header p {
            font-size: 1.1rem;
            opacity: 0.95;
        }

        /* ========== FILTER SECTION ========== */
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
            background: #f8f9fa;
            color: #2d3436;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            border: 2px solid transparent;
            font-size: 0.9rem;
        }

        .filter-btn i {
            margin-right: 0.5rem;
        }

        .filter-btn:hover {
            background: #FF6B35;
            color: white;
        }

        .filter-btn.active {
            background: #FF6B35;
            color: white;
            box-shadow: 0 4px 12px rgba(255,107,53,0.3);
        }

        /* ========== SEARCH BOX ========== */
        .search-box {
            position: relative;
        }

        .search-box input {
            border-radius: 50px;
            padding: 0.7rem 1.2rem;
            border: 2px solid #e9ecef;
            background: #f8f9fa;
            font-size: 0.9rem;
            width: 100%;
        }

        .search-box input:focus {
            border-color: #FF6B35;
            outline: none;
        }

        .search-box button {
            position: absolute;
            right: 5px;
            top: 5px;
            background: #FF6B35;
            border: none;
            border-radius: 50px;
            padding: 0.4rem 1.2rem;
            color: white;
            font-weight: 500;
        }

        /* ========== RESULT INFO ========== */
        .result-info {
            margin: 1.5rem 0;
            padding: 0.5rem 0;
            border-bottom: 2px solid #f0f0f0;
        }

        .result-info span {
            color: #FF6B35;
            font-weight: 700;
        }

        /* ========== FOOD CARD ========== */
        .food-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s;
            height: 100%;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .food-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
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
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(5px);
            color: white;
            padding: 0.3rem 1rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .rating {
            color: #fdcb6e;
            margin: 0.5rem 0;
            font-size: 0.9rem;
        }

        .rating span {
            color: #636e72;
            margin-left: 0.5rem;
            font-size: 0.8rem;
        }

        .price {
            font-size: 1.2rem;
            font-weight: 700;
            color: #FF6B35;
        }

        .location {
            color: #636e72;
            font-size: 0.8rem;
            margin: 0.5rem 0;
        }

        .location i {
            color: #FF6B35;
            margin-right: 0.3rem;
        }

        .food-description {
            color: #7f8c8d;
            font-size: 0.85rem;
            line-height: 1.4;
            margin: 0.5rem 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ========== EMPTY STATE ========== */
        .empty-state {
            text-align: center;
            padding: 4rem;
            background: white;
            border-radius: 20px;
        }

        .empty-state i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1rem;
        }

        /* ========== FOOTER ========== */
        footer {
            background: #1e272e;
            color: #d2dae2;
            padding: 4rem 0 2rem;
            margin-top: 4rem;
        }

        .footer-brand {
            font-size: 1.8rem;
            font-weight: 800;
            color: #FF6B35;
            margin-bottom: 1rem;
            display: inline-block;
        }

        footer h6 {
            color: white;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        footer a {
            color: #d2dae2;
            text-decoration: none;
        }

        footer a:hover {
            color: #FF6B35;
        }

        .social-icons a {
            display: inline-block;
            width: 35px;
            height: 35px;
            line-height: 35px;
            text-align: center;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            margin-right: 0.5rem;
        }

        /* ========== RESPONSIVE ========== */
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
        }
    </style>
</head>
<body>

<!-- ========== NAVBAR ========== -->
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <i class="fas fa-utensils fs-3 me-2" style="color: #FF6B35;"></i>
            <span class="fw-bold fs-4" style="color: #FF6B35;">Jogja Foodies</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link fw-bold <?= ($current_page == 'index.php') ? 'active-red' : '' ?>" href="index.php">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold <?= ($current_page == 'menu.php') ? 'active-red' : '' ?>" href="menu.php">
                        <i class="fas fa-utensils"></i> Menu
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="d-flex align-items-center" style="gap: 10px;">
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="dropdown d-inline-block">
                    <button class="btn rounded-pill px-3 dropdown-toggle" type="button" data-bs-toggle="dropdown" 
                            style="background: #FF6B35; color: white; border: none;">
                        <i class="fas fa-user-circle"></i> 
                        <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']) ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="my_orders.php">
                            <i class="fas fa-shopping-bag"></i> My Orders
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn rounded-pill px-3 me-2 fw-bold" style="background: #FF6B35; color: white;">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </a>
                <a href="register.php" class="btn rounded-pill px-3 fw-bold" style="border: 2px solid #FF6B35; color: #FF6B35;">
                    <i class="fas fa-user-plus"></i> Daftar
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- ========== PAGE HEADER ========== -->
<section class="page-header">
    <div class="container">
        <h1><i class="fas fa-utensils"></i> Menu Kuliner Jogja</h1>
        <p>Temukan berbagai makanan khas Yogyakarta yang menggugah selera</p>
    </div>
</section>

<!-- ========== FILTER SECTION ========== -->
<section class="filter-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="filter-group">
                    <a href="?category=all" class="filter-btn <?= (!isset($_GET['category']) || $_GET['category'] == 'all') ? 'active' : '' ?>">
                        <i class="fas fa-th-large"></i> Semua
                    </a>
                    <?php foreach($categories as $cat): ?>
                        <a href="?category=<?= urlencode($cat) ?>" 
                           class="filter-btn <?= ($category == $cat) ? 'active' : '' ?>">
                            <i class="fas fa-tag"></i> <?= htmlspecialchars($cat) ?>
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
                        <button type="submit">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- ========== MENU GRID ========== -->
<section class="container my-5">
    <div class="result-info">
        <p><i class="fas fa-chart-line"></i> Menampilkan <span><?= count($foods) ?></span> menu kuliner</p>
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
                            <h5 class="fw-bold mb-2"><?= htmlspecialchars($food['name']) ?></h5>
                            
                            <div class="rating">
                                <?php 
                                $full = floor($food['rating']);
                                for($i = 1; $i <= 5; $i++): ?>
                                    <?php if($i <= $full): ?>
                                        <i class="fas fa-star"></i>
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
                                <?= htmlspecialchars(substr($food['description'] ?? '', 0, 70)) ?>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="price">Rp <?= number_format($food['price'], 0, ',', '.') ?></span>
                                <a href="order.php?id=<?= $food['id'] ?>" class="btn-order">
                                    <i class="fas fa-shopping-cart"></i> Pesan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <h4>Tidak ada menu ditemukan</h4>
                    <p class="text-muted">Coba cari dengan kata kunci lain atau reset filter</p>
                    <a href="menu.php" class="btn btn-primary-custom mt-3">
                        <i class="fas fa-sync-alt"></i> Reset Filter
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ========== FOOTER ========== -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="footer-brand">
                    <i class="fas fa-utensils"></i> Jogja Foodies
                </div>
                <p>Discover the authentic taste of Yogyakarta through our curated culinary platform.</p>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            
            <div class="col-md-2 mb-4">
                <h6>Quick Links</h6>
                <ul class="list-unstyled">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            
            <div class="col-md-3 mb-4">
                <h6>Contact Info</h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-map-marker-alt"></i> Yogyakarta, Indonesia</li>
                    <li><i class="fas fa-envelope"></i> info@jogjafoodies.com</li>
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
        
        <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
        
        <div class="text-center">
            <p>&copy; 2024 Jogja Foodies. All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>