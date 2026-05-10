<?php
// file: my_orders.php
// ============================================
// HALAMAN RIWAYAT PESANAN USER - TANPA KODE ORDER & STATUS
// ============================================

session_start();
require_once 'config/koneksi.php';

// ========== CEK LOGIN ==========
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit(); 
}

$user_id = $_SESSION['user_id'];

// ========== CEK KOLOM created_at ==========
$check_column = mysqli_query($konek, "SHOW COLUMNS FROM orders LIKE 'created_at'");
$has_created_at = mysqli_num_rows($check_column) > 0;

// ========== AMBIL DATA PESANAN ==========
$query = "SELECT o.*, f.name as food_name 
          FROM orders o
          LEFT JOIN foods f ON o.food_id = f.id
          WHERE o.user_id = $user_id
          ORDER BY o.id DESC";

$result = mysqli_query($konek, $query);
$orders = [];

while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}

// ========== FUNGSI AMBIL GAMBAR ==========
function getFoodImageFromName($food_name) {
    $clean_name = strtolower(str_replace(' ', '', $food_name));
    
    if(strpos($clean_name, 'gudeg') !== false) return 'assets/gudeg.jpeg';
    if(strpos($clean_name, 'bakpia') !== false) return 'assets/bakpia.jpeg';
    if(strpos($clean_name, 'cendol') !== false) return 'assets/cendol.jpeg';
    if(strpos($clean_name, 'thiwul') !== false) return 'assets/thiwul.jpeg';
    if(strpos($clean_name, 'wedang') !== false) return 'assets/wedang.jpeg';
    if(strpos($clean_name, 'sate') !== false) return 'assets/sate.jpg';
    if(strpos($clean_name, 'ayam') !== false) return 'assets/ayam.jpeg';
    if(strpos($clean_name, 'ronde') !== false) return 'assets/ronde.jpeg';
    
    return 'assets/gudeg.jpeg';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Jogja Foodies</title>
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

        .btn-order-again {
            background: transparent;
            border: 2px solid #F5A3B0;
            border-radius: 50px;
            padding: 0.4rem 1rem;
            color: #F5A3B0;
            font-size: 0.8rem;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
        }

        .btn-order-again:hover {
            background: #F5A3B0;
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

        .order-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.3s;
            margin-bottom: 1.5rem;
            border: 1px solid #FFE2E8;
        }

        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(245,163,176,0.15);
        }

        .order-body {
            display: flex;
            padding: 1.5rem;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .order-image {
            width: 100px;
            height: 100px;
            border-radius: 12px;
            object-fit: cover;
        }

        .order-details {
            flex: 1;
        }

        .order-details h5 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #6B4E5E;
        }

        .order-meta {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 0.5rem;
        }

        .order-meta span {
            font-size: 0.8rem;
            color: #A58E98;
        }

        .order-total {
            font-weight: 700;
            color: #F5A3B0;
            font-size: 1.2rem;
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

        .empty-state h4 {
            color: #6B4E5E;
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

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }
            
            .order-body {
                flex-direction: column;
                text-align: center;
            }
            
            .order-image {
                margin: 0 auto;
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
                    <a class="nav-link fw-bold" href="menu.php">Menu</a>
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
                        <li><a class="dropdown-item active-red" href="my_orders.php">My Orders</a></li>
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
        <h1>My Orders</h1>
        <p>Riwayat pemesanan makanan Anda</p>
    </div>
</section>

<!-- ========== LIST ORDER ========== -->
<section class="container my-5">
    
    <?php if(count($orders) > 0): ?>
        
        <?php foreach($orders as $order): ?>
        <div class="order-card">
            <div class="order-body">
                <!-- GAMBAR MAKANAN -->
                <img src="<?php echo getFoodImageFromName($order['food_name']); ?>" 
                     alt="<?php echo htmlspecialchars($order['food_name']); ?>" 
                     class="order-image">
                
                <!-- DETAIL MAKANAN (TANPA NOMOR ORDER & STATUS) -->
                <div class="order-details">
                    <!-- NAMA MAKANAN + TANGGAL -->
                    <h5><?php echo htmlspecialchars($order['food_name']); ?></h5>
                    <div class="order-meta">
                        <span><i class="fas fa-calendar-alt"></i> 
                            <?php 
                                if($has_created_at && isset($order['created_at']) && !empty($order['created_at'])) {
                                    echo date('d M Y, H:i', strtotime($order['created_at']));
                                } elseif(isset($order['order_date']) && !empty($order['order_date'])) {
                                    echo date('d M Y, H:i', strtotime($order['order_date']));
                                } else {
                                    echo date('d M Y, H:i');
                                }
                            ?>
                        </span>
                        <span><i class="fas fa-box"></i> Jumlah: <?php echo $order['quantity']; ?>x</span>
                        <span><i class="fas fa-truck"></i> <?php echo $order['delivery_method'] == 'pickup' ? 'Ambil Sendiri' : 'Diantar'; ?></span>
                        <span><i class="fas fa-credit-card"></i> <?php echo strtoupper($order['payment_method']); ?></span>
                    </div>
                    <?php if(!empty($order['notes'])): ?>
                        <p class="text-muted small mt-2"><i class="fas fa-pen"></i> Catatan: <?php echo htmlspecialchars($order['notes']); ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- TOTAL HARGA & TOMBOL PESAN LAGI -->
                <div class="text-end">
                    <div class="order-total mb-2">Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></div>
                    <a href="order.php?id=<?php echo $order['food_id']; ?>" class="btn-order-again">
                        <i class="fas fa-shopping-cart"></i> Pesan Lagi
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
    <?php else: ?>
        
        <div class="empty-state">
            <i class="fas fa-receipt"></i>
            <h4 class="mt-3">Belum ada pesanan</h4>
            <p class="text-muted">Yuk, pesan makanan favorit Anda sekarang!</p>
            <a href="menu.php" class="btn btn-primary-custom mt-3">Mulai Pesan</a>
        </div>
        
    <?php endif; ?>
</section>

<!-- ========== FOOTER ========== -->
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