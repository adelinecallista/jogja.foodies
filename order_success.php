<?php
session_start();
require_once 'config/koneksi.php';

// Check if order success exists
if(!isset($_SESSION['order_success']) || !isset($_SESSION['order_data'])) {
    header("Location: menu.php");
    exit();
}

$order = $_SESSION['order_success_data'] ?? $_SESSION['order_data'];

// Clear session after displaying
unset($_SESSION['order_success']);
unset($_SESSION['order_data']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - Jogja Foodies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #FFD1DC;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .success-card {
            background: white;
            border-radius: 30px;
            padding: 3rem;
            text-align: center;
            max-width: 500px;
            margin: 2rem;
            box-shadow: 0 30px 60px rgba(245,163,176,0.25);
        }
        .success-icon {
            width: 100px;
            height: 100px;
            background: #F5A3B0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .success-icon i {
            font-size: 3rem;
            color: white;
        }
        .btn-home {
            background: #F5A3B0;
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
            transition: all 0.3s;
        }
        .btn-home:hover {
            background: #E8919F;
            color: white;
            transform: translateY(-2px);
        }
        h2 {
            color: #6B4E5E;
            margin-bottom: 0.5rem;
        }
        p {
            color: #A58E98;
        }
    </style>
</head>
<body>
    <div class="success-card">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        <h2>Pesanan Berhasil!</h2>
        <p>Terima kasih telah memesan di Jogja Foodies</p>
        <a href="menu.php" class="btn-home">Kembali ke Menu</a>
    </div>
</body>
</html>