<?php
// file: my_orders.php
session_start();
require_once 'config/koneksi.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ==============================================
// QUERY JOIN menggunakan MySQLi
// ==============================================
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

// Status badge color mapping
$status_colors = [
    'pending' => 'warning',
    'confirmed' => 'info',
    'processing' => 'primary',
    'completed' => 'success',
    'cancelled' => 'danger'
];

// Fungsi gambar
function getFoodImageFromName($food_name) {
    $clean_name = strtolower(str_replace(' ', '', $food_name));
    
    if(strpos($clean_name, 'gudeg') !== false) return 'assets/gudeg.jpeg';
    if(strpos($clean_name, 'bakpia') !== false) return 'assets/bakpia.jpeg';
    if(strpos($clean_name, 'cendol') !== false) return 'assets/cendol.jpeg';
    if(strpos($clean_name, 'thiwul') !== false) return 'assets/thiwul.jpeg';
    if(strpos($clean_name, 'wedang') !== false) return 'assets/wedang.jpeg';
    
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
    <style>
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
            margin: 0 0.5rem;
            transition: all 0.3s;
        }

        .nav-link:hover {
            color: #FF6B35 !important;
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #FF6B35, #FF8C42);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .dropdown-profile {
            background: transparent;
            border: none;
            padding: 0;
        }

        .dropdown-menu-custom {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
        }

        .page-header {
            background: linear-gradient(135deg, #FF6B35, #FF8C42);
            padding: 60px 0;
            color: white;
            text-align: center;
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .order-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.3s;
            margin-bottom: 1.5rem;
        }

        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .order-header {
            background: #f8f9fa;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .order-number {
            font-family: monospace;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .order-date {
            font-size: 0.8rem;
            color: #636e72;
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
        }

        .order-meta {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 0.5rem;
        }

        .order-meta span {
            font-size: 0.8rem;
            color: #636e72;
        }

        .order-total {
            font-weight: 700;
            color: #FF6B35;
            font-size: 1.2rem;
        }

        .status-badge {
            padding: 0.3rem 1rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .status-pending { background: #ffc107; color: #000; }
        .status-confirmed { background: #17a2b8; color: #fff; }
        .status-processing { background: #007bff; color: #fff; }
        .status-completed { background: #28a745; color: #fff; }
        .status-cancelled { background: #dc3545; color: #fff; }

        .btn-order-again {
            background: transparent;
            border: 2px solid #FF6B35;
            border-radius: 50px;
            padding: 0.3rem 1rem;
            color: #FF6B35;
            font-size: 0.8rem;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
        }

        .btn-order-again:hover {
            background: #FF6B35;
            color: white;
        }

        .btn-primary-custom {
            background: #FF6B35;
            border: none;
            padding: 0.5rem 1.8rem;
            border-radius: 50px;
            font-weight: 600;
            color: white;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary-custom:hover {
            background: #e55a2b;
            color: white;
        }

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

        footer {
            background: #1e272e;
            color: #d2dae2;
            padding: 3rem 0 2rem;
            margin-top: 3rem;
        }

        footer a {
            color: #d2dae2;
            text-decoration: none;
        }

        footer a:hover {
            color: #FF6B35;
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
<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1><i class="fas fa-receipt"></i> My Orders</h1>
        <p>Riwayat pemesanan makanan Anda</p>
    </div>
</section>

<!-- Orders List -->
<section class="container my-5">
    <?php if(count($orders) > 0): ?>
        <?php foreach($orders as $order): ?>
        <div class="order-card">
            <div class="order-header">
                <div>
                    <span class="order-number">#<?php echo htmlspecialchars($order['order_number']); ?></span>
                    <span class="order-date ms-3">
                        <i class="fas fa-calendar-alt"></i> <?php echo date('d M Y, H:i', strtotime($order['created_at'] ?? $order['order_date'] ?? 'now')); ?>
                    </span>
                </div>
                <div>
                    <span class="status-badge status-<?php echo $order['status']; ?>">
                        <?php echo ucfirst($order['status']); ?>
                    </span>
                </div>
            </div>
            <div class="order-body">
                <img src="<?php echo getFoodImageFromName($order['food_name']); ?>" alt="<?php echo htmlspecialchars($order['food_name']); ?>" class="order-image">
                <div class="order-details">
                    <h5><?php echo htmlspecialchars($order['food_name']); ?></h5>
                    <div class="order-meta">
                        <span><i class="fas fa-box"></i> Quantity: <?php echo $order['quantity']; ?>x</span>
                        <span><i class="fas fa-truck"></i> <?php echo ucfirst($order['delivery_method']); ?></span>
                        <span><i class="fas fa-credit-card"></i> <?php echo strtoupper($order['payment_method']); ?></span>
                    </div>
                    <?php if(!empty($order['notes'])): ?>
                        <p class="text-muted small mt-2"><i class="fas fa-pen"></i> Catatan: <?php echo htmlspecialchars($order['notes']); ?></p>
                    <?php endif; ?>
                </div>
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

<!-- Footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="footer-brand">
                    <i class="fas fa-utensils"></i> Jogja Foodies
                </div>
                <p>Discover the authentic taste of Yogyakarta.</p>
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