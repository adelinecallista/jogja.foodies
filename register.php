<?php
// file: register.php
session_start();
require_once 'config/koneksi.php';

// If already logged in, redirect to home
if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';
$form_data = [];

if($_SERVER["REQUEST_METHOD"] == "POST") {
    global $konek;
    
    $form_data = [
        'full_name' => mysqli_real_escape_string($konek, trim($_POST['full_name'] ?? '')),
        'username' => mysqli_real_escape_string($konek, trim($_POST['username'] ?? '')),
        'email' => mysqli_real_escape_string($konek, trim($_POST['email'] ?? ''))
    ];
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $terms = isset($_POST['terms']);
    
    $errors = [];
    
    if(empty($form_data['full_name'])) {
        $errors[] = "Nama lengkap harus diisi";
    }
    
    if(empty($form_data['username'])) {
        $errors[] = "Username harus diisi";
    } elseif(strlen($form_data['username']) < 3) {
        $errors[] = "Username minimal 3 karakter";
    }
    
    if(empty($form_data['email'])) {
        $errors[] = "Email harus diisi";
    } elseif(!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid";
    }
    
    if(empty($password)) {
        $errors[] = "Password harus diisi";
    } elseif(strlen($password) < 6) {
        $errors[] = "Password minimal 6 karakter";
    }
    
    if($password !== $confirm_password) {
        $errors[] = "Password dan konfirmasi password tidak sama";
    }
    
    if(!$terms) {
        $errors[] = "Anda harus menyetujui syarat & ketentuan";
    }
    
    if(empty($errors)) {
        $checkQuery = "SELECT id FROM users WHERE username = '$form_data[username]' OR email = '$form_data[email]'";
        $checkResult = mysqli_query($konek, $checkQuery);
        
        if(mysqli_num_rows($checkResult) > 0) {
            $errors[] = "Username atau email sudah terdaftar";
        } else {
            $insertQuery = "INSERT INTO users (username, email, password, full_name, role, avatar, created_at) 
                           VALUES ('$form_data[username]', '$form_data[email]', '$password', '$form_data[full_name]', 'user', 'default-avatar.png', NOW())";
            
            if(mysqli_query($konek, $insertQuery)) {
                $success = "Pendaftaran berhasil! Silakan login.";
                $form_data = [];
            } else {
                $errors[] = "Registrasi gagal: " . mysqli_error($konek);
            }
        }
    }
    
    if(!empty($errors)) {
        $error = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Jogja Foodies</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .register-container {
            max-width: 1200px;
            width: 100%;
            background: white;
            border-radius: 30px;
            box-shadow: 0 30px 60px rgba(245,163,176,0.25);
            overflow: hidden;
            display: flex;
            flex-wrap: wrap;
        }

        .form-side {
            flex: 1.2;
            padding: 3rem;
            background: white;
        }

        .info-side {
            flex: 0.8;
            background: #F5A3B0;
            padding: 3rem;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .info-side::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            animation: float 10s ease-in-out infinite;
        }

        .info-side::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -20%;
            width: 250px;
            height: 250px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-30px, -20px); }
        }

        .info-content {
            position: relative;
            z-index: 2;
        }

        .info-side .logo {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 2rem;
        }

        .info-side .logo i {
            margin-right: 0.5rem;
        }

        .info-side h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .info-side p {
            font-size: 0.9rem;
            line-height: 1.6;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .features-list {
            list-style: none;
            margin-top: 2rem;
        }

        .features-list li {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-size: 0.9rem;
        }

        .features-list li i {
            width: 30px;
            height: 30px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }

        .testimonial {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.2);
        }

        .testimonial p {
            font-style: italic;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .testimonial .author {
            font-weight: 600;
            font-size: 0.8rem;
        }

        .form-side h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #6B4E5E;
            margin-bottom: 0.5rem;
        }

        .form-side .subtitle {
            color: #A58E98;
            font-size: 0.85rem;
            margin-bottom: 2rem;
        }

        .form-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-row .form-group {
            flex: 1;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #6B4E5E;
            margin-bottom: 0.3rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid #FFE2E8;
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.3s;
            background: #FFF9FB;
        }

        .form-group input:focus {
            outline: none;
            border-color: #F5A3B0;
            background: white;
            box-shadow: 0 0 0 3px rgba(245,163,176,0.15);
        }

        .terms-group {
            margin: 1.5rem 0;
        }

        .terms-group label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            color: #A58E98;
            text-transform: none;
            font-weight: normal;
            cursor: pointer;
        }

        .terms-group input {
            width: 16px;
            height: 16px;
            accent-color: #F5A3B0;
        }

        .terms-group a {
            color: #F5A3B0;
            text-decoration: none;
        }

        .terms-group a:hover {
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            background: #F5A3B0;
            border: none;
            padding: 1rem;
            border-radius: 12px;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s;
            cursor: pointer;
        }

        .btn-submit:hover {
            background: #E8919F;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(245,163,176,0.4);
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #FFE2E8;
            font-size: 0.85rem;
            color: #A58E98;
        }

        .login-link a {
            color: #F5A3B0;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .alert-custom {
            border-radius: 12px;
            font-size: 0.8rem;
            padding: 0.8rem 1rem;
            margin-bottom: 1.5rem;
        }

        .alert-error {
            background: #FFF5F7;
            color: #F5A3B0;
            border-left: 4px solid #F5A3B0;
        }

        .alert-success {
            background: #E8F5E9;
            color: #66BB6A;
            border-left: 4px solid #66BB6A;
        }

        @media (max-width: 900px) {
            .register-container {
                flex-direction: column;
            }
            
            .info-side {
                order: -1;
                text-align: center;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .form-side {
                padding: 2rem;
            }
            
            .info-side {
                padding: 2rem;
            }
            
            .features-list {
                text-align: left;
                max-width: 300px;
                margin-left: auto;
                margin-right: auto;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 1rem;
            }
            
            .form-side {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="register-container">
    <div class="form-side">
        <h3>Create An Account</h3>
        <p class="subtitle">Sign up to continue</p>
        
        <?php if($error): ?>
        <div class="alert-custom alert-error">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <?php if($success): ?>
        <div class="alert-custom alert-success">
            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            <br><small><a href="login.php" style="color: #66BB6A; font-weight: 600;">Login here</a></small>
        </div>
        <?php endif; ?>
        
        <?php if(!$success): ?>
        <form method="POST" action="">
            <div class="form-row">
                <div class="form-group">
                    <label>FULL NAME</label>
                    <input type="text" name="full_name" placeholder="Your full name" 
                           value="<?php echo htmlspecialchars($form_data['full_name'] ?? ''); ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>USERNAME</label>
                    <input type="text" name="username" placeholder="Choose a username" 
                           value="<?php echo htmlspecialchars($form_data['username'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>EMAIL</label>
                    <input type="email" name="email" placeholder="Your email address" 
                           value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>PASSWORD</label>
                    <input type="password" name="password" id="password" placeholder="Create a password" required>
                </div>
                <div class="form-group">
                    <label>CONFIRM PASSWORD</label>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" required>
                </div>
            </div>
            
            <div class="terms-group">
                <label>
                    <input type="checkbox" name="terms" required>
                    <span>Saya setuju dengan <a href="#">Syarat & Ketentuan</a> dan <a href="#">Kebijakan Privasi</a></span>
            </div>
            
            <button type="submit" class="btn-submit">
                <i class="fas fa-user-plus"></i> SUBMIT
            </button>
        </form>
        
        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>