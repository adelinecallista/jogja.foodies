<?php
// file: order.php
session_start();
require_once 'config/koneksi.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get food ID from URL
$food_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($food_id <= 0) {
    header("Location: menu.php");
    exit();
}

// Get quantity from URL
$quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;
if($quantity < 1) $quantity = 1;
if($quantity > 99) $quantity = 99;

// Get food data from database (MySQLi)
$query = "SELECT * FROM foods WHERE id = $food_id";
$result = mysqli_query($konek, $query);
$food = mysqli_fetch_assoc($result);

if(!$food) {
    header("Location: menu.php");
    exit();
}

// Get user data
$user_query = "SELECT * FROM users WHERE id = " . $_SESSION['user_id'];
$user_result = mysqli_query($konek, $user_query);
$user = mysqli_fetch_assoc($user_result);

// Fungsi gambar
function getFoodImage($food_name) {
    $clean_name = strtolower(str_replace(' ', '', $food_name));
    
    if(strpos($clean_name, 'gudeg') !== false) return 'assets/gudeg.jpeg';
    if(strpos($clean_name, 'bakpia') !== false) return 'assets/bakpia.jpeg';
    if(strpos($clean_name, 'cendol') !== false) return 'assets/cendol.jpeg';
    if(strpos($clean_name, 'thiwul') !== false) return 'assets/thiwul.jpeg';
    if(strpos($clean_name, 'wedang') !== false) return 'assets/wedang.jpeg';
    
    return 'assets/gudeg.jpeg';
}

// Get form values from POST or default
$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'cash';
$notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';
$delivery_fee = 0;
$total = ($food['price'] * $quantity) + $delivery_fee;
$error = '';

// Process order if form submitted
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_order'])) {
    if($quantity < 1) {
        $error = "Jumlah minimal 1";
    } else {
        // Generate unique order number
        $order_number = 'JGF-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        
        // Insert order ke database (MySQLi) - TANPA created_at
        $insert_query = "INSERT INTO orders (order_number, user_id, food_id, food_name, quantity, price_per_item, delivery_fee, total_amount, delivery_method, payment_method, notes, status) 
                VALUES ('$order_number', {$_SESSION['user_id']}, $food_id, '{$food['name']}', $quantity, {$food['price']}, $delivery_fee, $total, '$delivery_method', '$payment_method', '$notes', 'pending')"; 
        
        if(mysqli_query($konek, $insert_query)) {
            // Get the inserted order ID
            $order_id = mysqli_insert_id($konek);
            
            // Save to session for success page
            $_SESSION['order_success'] = true;
            $_SESSION['order_data'] = [
                'order_id' => $order_id,
                'order_number' => $order_number,
                'food_id' => $food['id'],
                'food_name' => $food['name'],
                'quantity' => $quantity,
                'price_per_item' => $food['price'],
                'total' => $total,
                'delivery_method' => $delivery_method,
                'payment_method' => $payment_method,
                'notes' => $notes,
                'delivery_fee' => $delivery_fee
            ];
            
            header("Location: order_success.php");
            exit();
        } else {
            $error = "Gagal memproses pesanan: " . mysqli_error($konek);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Makanan - Jogja Foodies</title>
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
            background: #FFD1DC;
            min-height: 100vh;
            padding: 2rem;
        }

        .order-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(245,163,176,0.9);
            backdrop-filter: blur(10px);
            padding: 0.7rem 1.5rem;
            border-radius: 50px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 1.5rem;
            transition: all 0.3s;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .back-btn:hover {
            background: #F5A3B0;
            color: white;
            transform: translateX(-5px);
        }

        .order-card {
            background: white;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(245,163,176,0.25);
            display: flex;
            flex-wrap: wrap;
        }

        .food-preview {
            flex: 1;
            min-width: 300px;
            background: #FFD1DC;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .food-preview::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .food-image {
            width: 250px;
            height: 250px;
            border-radius: 30px;
            object-fit: cover;
            box-shadow: 0 20px 40px rgba(245,163,176,0.3);
            position: relative;
            z-index: 2;
            border: 4px solid rgba(255,255,255,0.5);
        }

        .food-category {
            position: relative;
            z-index: 2;
            margin-top: 1.5rem;
            background: rgba(255,255,255,0.3);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            color: #6B4E5E;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .food-rating {
            position: relative;
            z-index: 2;
            margin-top: 0.8rem;
            color: #FFB7C5;
            font-size: 0.9rem;
        }

        .food-rating span {
            color: #8A7A82;
            margin-left: 0.3rem;
        }

        .order-form {
            flex: 1.2;
            min-width: 350px;
            padding: 2rem;
            background: white;
        }

        .order-form h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #6B4E5E;
            margin-bottom: 0.3rem;
        }

        .food-location {
            color: #A58E98;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
        }

        .food-location i {
            color: #F5A3B0;
            margin-right: 0.3rem;
        }

        .price-container {
            background: #FFF5F7;
            padding: 1rem;
            border-radius: 15px;
            margin-bottom: 1.5rem;
        }

        .price-label {
            font-size: 0.8rem;
            color: #A58E98;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .food-price {
            font-size: 2rem;
            font-weight: 800;
            color: #F5A3B0;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #6B4E5E;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }

        .form-group label i {
            color: #F5A3B0;
            margin-right: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 12px;
            border: 2px solid #FFE2E8;
            padding: 0.8rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s;
            width: 100%;
            background: #FFF9FB;
        }

        .form-control:focus, .form-select:focus {
            border-color: #F5A3B0;
            outline: none;
            box-shadow: 0 0 0 3px rgba(245,163,176,0.2);
        }

        .quantity-wrapper {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .quantity-btn {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: #FFF9FB;
            border: 2px solid #FFE2E8;
            font-size: 1.2rem;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #6B4E5E;
            transition: all 0.3s;
        }

        .quantity-btn:hover {
            background: #F5A3B0;
            border-color: #F5A3B0;
            color: white;
        }

        .quantity-value {
            font-size: 1.2rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            background: #FFF9FB;
            border-radius: 12px;
            border: 2px solid #FFE2E8;
            min-width: 80px;
            text-align: center;
            color: #6B4E5E;
        }

        .total-section {
            background: #FFF5F7;
            padding: 1rem;
            border-radius: 15px;
            margin: 1.5rem 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-label {
            font-weight: 600;
            color: #6B4E5E;
        }

        .total-amount {
            font-size: 1.5rem;
            font-weight: 800;
            color: #F5A3B0;
        }

        .btn-order {
            width: 100%;
            background: #F5A3B0;
            border: none;
            padding: 1rem;
            border-radius: 12px;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s;
            margin-bottom: 1rem;
            cursor: pointer;
        }

        .btn-order:hover {
            background: #E8919F;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(245,163,176,0.4);
        }

        .btn-cancel {
            width: 100%;
            background: transparent;
            border: 2px solid #FFE2E8;
            padding: 0.8rem;
            border-radius: 12px;
            color: #A58E98;
            font-weight: 500;
            transition: all 0.3s;
            cursor: pointer;
            text-align: center;
            display: inline-block;
            text-decoration: none;
        }

        .btn-cancel:hover {
            border-color: #F5A3B0;
            color: #F5A3B0;
        }

        .payment-methods {
            margin: 1rem 0;
        }

        .payment-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: #A58E98;
            margin-bottom: 0.8rem;
            text-transform: uppercase;
        }

        .payment-options {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .payment-option {
            flex: 1;
            padding: 0.7rem;
            border-radius: 10px;
            background: #FFF9FB;
            border: 2px solid #FFE2E8;
            transition: all 0.3s;
            cursor: pointer;
            text-align: center;
            color: #6B4E5E;
        }

        .payment-option input {
            margin-right: 0.5rem;
            accent-color: #F5A3B0;
        }

        .alert-error {
            background: #FFF5F7;
            color: #F5A3B0;
            border-left: 4px solid #F5A3B0;
            padding: 0.8rem 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }
            
            .food-image {
                width: 180px;
                height: 180px;
            }
            
            .order-form {
                padding: 1.5rem;
            }
            
            .order-form h2 {
                font-size: 1.4rem;
            }
            
            .food-price {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="order-container">
    <a href="menu.php" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Menu
    </a>
    
    <div class="order-card">
        <!-- kelas bagian makanan -->
        <div class="food-preview">
            <img src="<?php echo getFoodImage($food['name']); ?>" alt="<?php echo htmlspecialchars($food['name']); ?>" class="food-image">
            <div class="food-category">
                <i class="fas fa-tag"></i> <?php echo htmlspecialchars($food['category']); ?>
            </div>
            <div class="food-rating">
                <?php for($i = 1; $i <= 5; $i++): ?>
                    <?php if($i <= $food['rating']): ?>
                        <i class="fas fa-star"></i>
                    <?php elseif($i - 0.5 <= $food['rating']): ?>
                        <i class="fas fa-star-half-alt"></i>
                    <?php else: ?>
                        <i class="far fa-star"></i>
                    <?php endif; ?>
                <?php endfor; ?>
                <span>(<?php echo number_format($food['rating'], 1); ?>)</span>
            </div>
        </div>
        
        <!-- kelas bagian formulir pemesanan -->
        <div class="order-form">
            <h2><?php echo htmlspecialchars($food['name']); ?></h2>
            <div class="food-location">
                <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($food['location']); ?>
            </div>
            
            <div class="price-container">
                <div class="price-label">Harga per porsi</div>
                <div class="food-price">Rp <?php echo number_format($food['price'], 0, ',', '.'); ?></div>
            </div>
            
            <?php if($error): ?>
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <input type="hidden" name="food_id" value="<?php echo $food['id']; ?>">
                
                <!-- jumlah pesanan -->
                <div class="form-group">
                    <label><i class="fas fa-shopping-cart"></i> Jumlah Pesanan</label>
                    <div class="quantity-wrapper">
                        <a href="?id=<?php echo $food_id; ?>&quantity=<?php echo max(1, $quantity-1); ?>" class="quantity-btn">-</a>
                        <span class="quantity-value"><?php echo $quantity; ?></span>
                        <a href="?id=<?php echo $food_id; ?>&quantity=<?php echo min(99, $quantity+1); ?>" class="quantity-btn">+</a>
                    </div>
                </div>
                
                <!-- catatan tambahan -->
                <div class="form-group">
                    <label><i class="fas fa-pen"></i> Catatan (opsional)</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="Misal: tidak pedas, porsi banyak, tambah sambal..."><?php echo htmlspecialchars($notes); ?></textarea>
                </div>
                
                <!-- pembayaran -->
                <div class="payment-methods">
                    <div class="payment-title">Metode Pembayaran</div>
                    <div class="payment-options">
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="cash" <?php echo $payment_method == 'cash' ? 'checked' : ''; ?>> Cash
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="qris" <?php echo $payment_method == 'qris' ? 'checked' : ''; ?>> QRIS
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="transfer" <?php echo $payment_method == 'transfer' ? 'checked' : ''; ?>> Transfer
                        </label>
                    </div>
                </div>
                
                <!-- Total -->
                <div class="total-section">
                    <span class="total-label">Total Pembayaran</span>
                    <span class="total-amount">
                        Rp <?php echo number_format($total, 0, ',', '.'); ?>
                    </span>
                </div>
                
                <!-- konfirm -->
                <button type="submit" name="confirm_order" class="btn-order">
                    <i class="fas fa-check-circle"></i> Konfirmasi Pesanan
                </button>
                <a href="menu.php" class="btn-cancel">
                    <i class="fas fa-times"></i> Batal
                </a>
            </form>
        </div>
    </div>
</div>

</body>
</html>