<?php
// file: index.php
session_start();
require_once 'config/koneksi.php';

// Inisialisasi koneksi (sesuai modul)
$konek = getKoneksi();

// Get popular foods from database (ambil 6 makanan untuk featured)
$query = "SELECT id, name, description, price, category, location, rating FROM foods ORDER BY rating DESC LIMIT 6";
$result = mysqli_query($konek, $query);
$popular_foods = [];
while ($row = mysqli_fetch_assoc($result)) {
    $popular_foods[] = $row;
}

// Get top 3 rated foods
$top_query = "SELECT id, name, description, location, rating FROM foods ORDER BY rating DESC LIMIT 3";
$top_result = mysqli_query($konek, $top_query);
$top_foods = [];
while ($row = mysqli_fetch_assoc($top_result)) {
    $top_foods[] = $row;
}

// Get stats from database
$stats_query = "SELECT 
    (SELECT COUNT(*) FROM foods) as total_foods,
    (SELECT COUNT(*) FROM users) as total_users,
    (SELECT AVG(rating) FROM foods) as avg_rating";
$stats_result = mysqli_query($konek, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// Set default values jika NULL
if (!$stats['total_foods']) $stats['total_foods'] = 0;
if (!$stats['total_users']) $stats['total_users'] = 0;
if (!$stats['avg_rating']) $stats['avg_rating'] = 0;

$current_page = basename($_SERVER['PHP_SELF']);

// Fungsi untuk mendapatkan path gambar (sederhana)
function getFoodImage($food_name) {
    $clean_name = strtolower(str_replace(' ', '', $food_name));
    
    $image_map = [
        'bakpia' => 'assets/bakpia.jpeg',
        'cendol' => 'assets/cendol.jpeg',
        'gudeg' => 'assets/gudeg.jpeg',
        'thiwul' => 'assets/thiwul.jpeg',
        'wedang' => 'assets/wedang.jpeg',
    ];
    
    foreach($image_map as $key => $image) {
        if(strpos($clean_name, $key) !== false) {
            return $image;
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
    <title>Jogja Foodies - Discover Authentic Yogyakarta Cuisine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Sama seperti CSS Anda sebelumnya, saya pertahankan */
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

        .btn-outline-dark {
            border-radius: 50px;
            padding: 0.5rem 1.8rem;
            border: 2px solid #FF6B35;
            color: #FF6B35;
            background: transparent;
            transition: all 0.3s;
        }

        .btn-outline-dark:hover {
            background: #FF6B35;
            border-color: #FF6B35;
            color: white;
        }

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

        .cursor-pointer {
            cursor: pointer;
        }

        .hero {
            background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 50%, #FFD166 100%);
            min-height: 90vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            color: white;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        .btn-hero-primary {
            background: white;
            color: #FF6B35;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            margin-right: 1rem;
            transition: all 0.3s;
            border: none;
            text-decoration: none;
            display: inline-block;
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            color: #FF6B35;
        }

        .btn-hero-outline {
            background: transparent;
            border: 2px solid white;
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-hero-outline:hover {
            background: white;
            color: #FF6B35;
            transform: translateY(-3px);
        }

        .stats-section {
            background: white;
            padding: 4rem 0;
            margin-top: -50px;
            position: relative;
            z-index: 10;
        }

        .stat-card {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: #FF6B35;
        }

        .stat-label {
            color: #636e72;
            font-weight: 500;
            margin-top: 0.5rem;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-align: center;
            color: #2d3436;
        }

        .section-subtitle {
            text-align: center;
            color: #636e72;
            margin-bottom: 3rem;
        }

        .top-rated-section {
            background: linear-gradient(135deg, #fff5f0 0%, #ffe8e0 100%);
            padding: 5rem 0;
            margin: 3rem 0;
        }

        .top-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            height: 100%;
        }

        .top-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(255,107,53,0.15);
        }

        .rank {
            font-size: 3rem;
            font-weight: 800;
            display: inline-block;
            width: 70px;
            height: 70px;
            line-height: 70px;
            border-radius: 50%;
            margin-bottom: 1rem;
        }

        .rank-1 {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: white;
        }

        .rank-2 {
            background: linear-gradient(135deg, #C0C0C0, #A8A8A8);
            color: white;
        }

        .rank-3 {
            background: linear-gradient(135deg, #CD7F32, #B87333);
            color: white;
        }

        .rating {
            color: #fdcb6e;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }

        .rating span {
            color: #636e72;
            margin-left: 0.5rem;
        }

        .location {
            color: #636e72;
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .location i {
            color: #FF6B35;
            margin-right: 0.3rem;
        }

        .cta-section {
            background: linear-gradient(135deg, #FF6B35, #FF8C42);
            padding: 5rem 0;
            border-radius: 0;
            color: white;
        }

        .btn-cta {
            background: white;
            color: #FF6B35;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            text-decoration: none;
            display: inline-block;
        }

        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            color: #FF6B35;
        }

        footer {
            background: #1e272e;
            color: #d2dae2;
            padding: 4rem 0 2rem;
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
            transition: all 0.3s;
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
            transition: all 0.3s;
        }

        .social-icons a:hover {
            background: #FF6B35;
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
            
            .btn-hero-primary, .btn-hero-outline {
                padding: 0.7rem 1.5rem;
                margin-bottom: 1rem;
                display: block;
                width: 100%;
                text-align: center;
            }
            
            .stats-section {
                margin-top: 0;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
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
                    <a class="nav-link fw-bold <?= ($current_page == 'index.php') ? 'active-red' : '' ?>" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="menu.php">Menu</a>
                </li>
            </ul>
        </div>
        
        <div class="d-flex align-items-center" style="gap: 10px;">
            <i class="bi bi-search fs-5 cursor-pointer" style="cursor: pointer; color: #FF6B35;"></i>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="dropdown d-inline-block">
                    <button class="btn rounded-pill px-3 dropdown-toggle" type="button" data-bs-toggle="dropdown" style="background: #FF6B35; color: white; border: none;">
                        <i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']) ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="my_orders.php"><i class="fas fa-shopping-bag"></i> My Orders</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn rounded-pill px-3 me-2 fw-bold" style="background: #FF6B35; color: white;">Masuk</a>
                <a href="register.php" class="btn rounded-pill px-3 fw-bold" style="border: 2px solid #FF6B35; color: #FF6B35;">Daftar</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="container hero-content">
        <div class="row">
            <div class="col-lg-7">
                <h1>Discover Authentic <br>Yogyakarta Cuisine</h1>
                <p class="lead">Explore the rich flavors of Jogja's culinary heritage. From legendary Gudeg to hidden gem Angkringan, find your next favorite dish with Jogja Foodies.</p>
                <div>
                    <a href="menu.php" class="btn-hero-primary">
                        Explore Menu <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="#" class="btn-hero-outline">
                        Watch Story <i class="fas fa-play"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($stats['total_foods']); ?>+</div>
                    <div class="stat-label">Food Vendors</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($stats['total_users']); ?>+</div>
                    <div class="stat-label">Happy Foodies</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($stats['total_foods']); ?>+</div>
                    <div class="stat-label">Authentic Dishes</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($stats['avg_rating'], 1); ?></div>
                    <div class="stat-label">Rating Average</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Top Rated Section -->
<section class="top-rated-section">
    <div class="container">
        <h2 class="section-title">⭐ Top Rated <span style="color: #FF6B35;">Dishes</span></h2>
        <p class="section-subtitle">Highest rated foods by our foodies community</p>
        
        <div class="row g-4">
            <?php if(count($top_foods) >= 1): ?>
                <?php 
                $ranks = ['rank-1', 'rank-2', 'rank-3'];
                $icons = ['fa-crown', 'fa-medal', 'fa-award'];
                foreach($top_foods as $index => $food): 
                ?>
                <div class="col-md-4">
                    <div class="top-card">
                        <div class="rank <?php echo $ranks[$index]; ?>">
                            <i class="fas <?php echo $icons[$index]; ?>"></i>
                        </div>
                        <h4 class="fw-bold mt-3"><?php echo htmlspecialchars($food['name']); ?></h4>
                        <div class="rating mb-2">
                            <?php 
                            $full = floor($food['rating']);
                            $half = ($food['rating'] - $full) >= 0.5;
                            for($i = 1; $i <= 5; $i++):
                                if($i <= $full): ?>
                                    <i class="fas fa-star"></i>
                                <?php elseif($half && $i == $full + 1): ?>
                                    <i class="fas fa-star-half-alt"></i>
                                <?php else: ?>
                                    <i class="far fa-star"></i>
                                <?php endif;
                            endfor; ?>
                            <span>(<?php echo number_format($food['rating'], 1); ?>)</span>
                        </div>
                        <p class="text-muted"><?php echo htmlspecialchars(substr($food['description'] ?? '', 0, 50)); ?></p>
                        <div class="location">
                            <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($food['location']); ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info">Belum ada data rating. Silakan tambahkan data ke database.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-3">Ready to Explore Jogja's Culinary?</h2>
                <p class="lead mb-0">Join thousands of foodies who discover authentic flavors every day</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="register.php" class="btn-cta">
                    Join Now <i class="fas fa-user-plus"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
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
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h6>Contact Info</h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-map-marker-alt"></i> Yogyakarta, Indonesia</li>
                    <li><i class="fas fa-envelope"></i> info@jogjafoodies.com</li>
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
        <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
        <div class="text-center">
            <p class="mb-0">&copy; 2024 Jogja Foodies. All rights reserved. Crafted with <i class="fas fa-heart text-danger"></i> for Jogja's culinary lovers</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>