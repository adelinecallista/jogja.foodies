<?php
// file: login.php
session_start();
require_once 'config/koneksi.php';  // Menggunakan koneksi.php

// Jika sudah login, redirect ke index
if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gunakan koneksi dari koneksi.php
    global $konek;
    
    $username = mysqli_real_escape_string($konek, trim($_POST['username']));
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);
    
    // Query ke database menggunakan MySQLi (sesuai modul 7)
    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";
    $result = mysqli_query($konek, $query);
    
    if(mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // VERIFIKASI PASSWORD PLAIN TEXT (LANGSUNG DIBANDINGKAN)
        if($password == $user['password']) {
            // Set session (sesuai modul 6)
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['status'] = "login";  // Sesuai modul 6 halaman 3
            
            // Remember me (cookie untuk 30 hari)
            if($remember) {
                setcookie('user_id', $user['id'], time() + (86400 * 30), "/");
                setcookie('username', $user['username'], time() + (86400 * 30), "/");
            }
            
            // Redirect ke index.php
            header("Location: index.php");
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username atau Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jogja Foodies</title>
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
            background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 50%, #FFD166 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.2;
            pointer-events: none;
        }

        .floating-icon {
            position: absolute;
            opacity: 0.1;
            font-size: 6rem;
            pointer-events: none;
        }

        .icon-1 { top: 10%; left: 5%; animation: float 8s ease-in-out infinite; }
        .icon-2 { bottom: 15%; right: 8%; animation: float 10s ease-in-out infinite reverse; }
        .icon-3 { top: 50%; left: 3%; animation: float 12s ease-in-out infinite; }
        .icon-4 { bottom: 30%; right: 5%; animation: float 7s ease-in-out infinite reverse; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }

        .login-wrapper {
            width: 100%;
            max-width: 500px;
            margin: 2rem;
            position: relative;
            z-index: 10;
        }

        .login-card {
            background: white;
            border-radius: 30px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.2);
            overflow: hidden;
            transition: all 0.3s;
        }

        .card-header {
            background: white;
            padding: 2rem 2rem 1rem;
            text-align: center;
            border-bottom: none;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #FF6B35, #FF8C42);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 20px rgba(255,107,53,0.3);
        }

        .logo-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .card-header h3 {
            font-size: 1.8rem;
            font-weight: 800;
            color: #2d3436;
            margin-bottom: 0.5rem;
        }

        .card-header p {
            color: #636e72;
            font-size: 0.9rem;
        }

        .card-body {
            padding: 1.5rem 2rem 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            font-weight: 600;
            color: #2d3436;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.9rem;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 42px;
            color: #FF6B35;
            font-size: 1rem;
        }

        .form-control {
            border-radius: 15px;
            padding: 0.9rem 1rem 0.9rem 2.8rem;
            border: 2px solid #e9ecef;
            background: #f8f9fa;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #FF6B35;
            background: white;
            box-shadow: 0 0 0 4px rgba(255,107,53,0.1);
        }

        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            font-size: 0.85rem;
            color: #636e72;
        }

        .checkbox-label input {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #FF6B35;
        }

        .forgot-link {
            color: #FF6B35;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .forgot-link:hover {
            color: #e55a2b;
            text-decoration: underline;
        }

        .btn-login {
            background: linear-gradient(135deg, #FF6B35, #FF8C42);
            border: none;
            border-radius: 15px;
            padding: 0.9rem;
            font-weight: 700;
            font-size: 1rem;
            width: 100%;
            color: white;
            transition: all 0.3s;
            margin-bottom: 1rem;
            cursor: pointer;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255,107,53,0.3);
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e9ecef;
        }

        .divider span {
            padding: 0 1rem;
            color: #b2bec3;
            font-size: 0.8rem;
        }

        .social-login {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .social-btn {
            flex: 1;
            padding: 0.7rem;
            border-radius: 12px;
            border: 2px solid #e9ecef;
            background: white;
            transition: all 0.3s;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .social-btn:hover {
            border-color: #FF6B35;
            transform: translateY(-2px);
        }

        .social-btn i {
            font-size: 1.2rem;
        }

        .social-btn.google i { color: #DB4437; }
        .social-btn.facebook i { color: #4267B2; }

        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
        }

        .register-link p {
            color: #636e72;
            font-size: 0.9rem;
        }

        .register-link a {
            color: #FF6B35;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .alert-custom {
            border-radius: 15px;
            border: none;
            background: #fff5f0;
            color: #FF6B35;
            font-size: 0.85rem;
            padding: 0.8rem 1rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #FF6B35;
        }

        .alert-custom i {
            margin-right: 0.5rem;
        }

        .login-card {
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 576px) {
            .login-wrapper {
                margin: 1rem;
            }
            .card-body {
                padding: 1.5rem;
            }
            .social-login {
                flex-direction: column;
            }
            .options-row {
                flex-direction: column;
                gap: 0.8rem;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

<div class="floating-icon icon-1">
    <i class="fas fa-utensils"></i>
</div>
<div class="floating-icon icon-2">
    <i class="fas fa-mug-hot"></i>
</div>
<div class="floating-icon icon-3">
    <i class="fas fa-drumstick-bite"></i>
</div>
<div class="floating-icon icon-4">
    <i class="fas fa-coffee"></i>
</div>

<div class="login-wrapper">
    <div class="login-card">
        <div class="card-header">
            <div class="logo-icon">
                <i class="fas fa-utensils"></i>
            </div>
            <h3>Welcome Back!</h3>
            <p>Masuk ke akun Jogja Foodies Anda</p>
        </div>
        
        <div class="card-body">
            <?php if($error): ?>
            <div class="alert-custom">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <?php if(isset($_GET['registered']) && $_GET['registered'] == 'success'): ?>
            <div class="alert-custom" style="background: #e8f8f5; color: #00b894; border-left-color: #00b894;">
                <i class="fas fa-check-circle"></i> Pendaftaran berhasil! Silakan login.
            </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label>Username atau Email</label>
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username atau email" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required autofocus>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                </div>
                
                <div class="options-row">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember"> Ingat saya
                    </label>
                    <a href="#" class="forgot-link">Lupa password?</a>
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            
            <div class="divider">
                <span>atau login dengan</span>
            </div>
            
            <div class="social-login">
                <button class="social-btn google" onclick="alert('Login dengan Google akan segera hadir!')">
                    <i class="fab fa-google"></i> Google
                </button>
                <button class="social-btn facebook" onclick="alert('Login dengan Facebook akan segera hadir!')">
                    <i class="fab fa-facebook-f"></i> Facebook
                </button>
            </div>
            
            <div class="register-link">
                <p>Belum punya akun? <a href="register.php">Daftar sekarang</a></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>