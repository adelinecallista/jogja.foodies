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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #FFF5F7;  /* soft pastel pink background */
            color: #4A4A4A;
        }

        /* Pastel Pink Color Palette */
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

        .navbar-brand i {
            color: #F5A3B0;
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

        .btn-outline-dark {
            border-radius: 50px;
            padding: 0.5rem 1.8rem;
            border: 2px solid #FFB7C5;
            color: #F5A3B0;
            background: transparent;
            transition: all 0.3s;
        }

        .btn-outline-dark:hover {
            background: #FFB7C5;
            border-color: #FFB7C5;
            color: white;
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

        .cursor-pointer {
            cursor: pointer;
        }

        /* Hero Section - Solid Pastel Pink (No gradient) */
        .hero {
            background: #FFD1DC;  /* solid pastel blush */
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,245,247,0.4)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.2;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            color: #5A4A4F;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            color: #6B4E5E;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            color: #6B4E5E;
        }

        .btn-hero-primary {
            background: #FFFFFF;
            color: #F5A3B0;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            margin-right: 1rem;
            transition: all 0.3s;
            border: none;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(245,163,176,0.2);
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(245,163,176,0.3);
            color: #E8919F;
        }

        .btn-hero-outline {
            background: transparent;
            border: 2px solid #FFFFFF;
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
            color: #F5A3B0;
            transform: translateY(-3px);
        }

        .stats-section {
            background: #FFFFFF;
            padding: 4rem 0;
            margin-top: -50px;
            position: relative;
            z-index: 10;
            border-radius: 40px 40px 0 0;
        }

        .stat-card {
            text-align: center;
            padding: 2rem;
            background: #FFF9FB;
            border-radius: 28px;
            box-shadow: 0 8px 24px rgba(245,163,176,0.08);
            transition: all 0.3s;
            border: 1px solid #FFE2E8;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(245,163,176,0.15);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: #F5A3B0;
        }

        .stat-label {
            color: #8A7A82;
            font-weight: 500;
            margin-top: 0.5rem;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-align: center;
            color: #6B4E5E;
        }

        .section-subtitle {
            text-align: center;
            color: #A58E98;
            margin-bottom: 3rem;
        }

        /* Top Rated Section - Pastel Mauve solid (no gradient) */
        .top-rated-section {
            background: #FDF2F5;  /* solid pastel pinkish white */
            padding: 5rem 0;
            margin: 3rem 0;
        }

        .top-card {
            background: #FFFFFF;
            border-radius: 28px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s;
            box-shadow: 0 5px 20px rgba(245,163,176,0.08);
            height: 100%;
            border: 1px solid #FFE2E8;
        }

        .top-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(245,163,176,0.2);
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
            background: #FFD1DC;
            color: #F5A3B0;
        }

        .rank-2 {
            background: #F0E2E8;
            color: #CDA2B0;
        }

        .rank-3 {
            background: #EBD8E0;
            color: #C28B9A;
        }

        .rating {
            color: #F4C2C2;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }

        .rating span {
            color: #A58E98;
            margin-left: 0.5rem;
        }

        .location {
            color: #A58E98;
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .location i {
            color: #F5A3B0;
            margin-right: 0.3rem;
        }

        /* CTA Section - solid pastel pink (no gradient) */
        .cta-section {
            background: #FFD1DC;  /* solid pastel blush */
            padding: 5rem 0;
            border-radius: 0;
            color: #6B4E5E;
        }

        .btn-cta {
            background: white;
            color: #F5A3B0;
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
            box-shadow: 0 10px 25px rgba(255,209,220,0.4);
            color: #E8919F;
        }

        footer {
            background: #F8E9EE;  /* soft pastel pink footer */
            color: #7A5C68;
            padding: 4rem 0 2rem;
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
            transition: all 0.3s;
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

            .navbar-brand img {
                height: 35px;
            }

            .footer-brand img {
                height: 32px;
            }
        }

        /* Logo fallback styling jika gambar tidak ditemukan */
        .logo-placeholder {
            background: #F5A3B0;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>

<!-- Navbar dengan Logo Gambar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <!-- Logo menggunakan file logo.jpeg -->
            <img src="assets/logo.jpeg" alt="Jogja Foodies Logo" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Crect width=%22100%22 height=%22100%22 fill=%22%23F5A3B0%22/%3E%3Ctext x=%2250%22 y=%2267%22 text-anchor=%22middle%22 fill=%22white%22 font-size=%2250%22 font-weight=%22bold%22%3E🍴%3C/text%3E%3C/svg%3E';">
            <span class="fw-bold fs-4" style="color: #F5A3B0;">Jogja Foodies</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link fw-bold active-red" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="menu.php">Menu</a>
                </li>
            </ul>
        </div>
        
        <div class="d-flex align-items-center" style="gap: 10px;">
            <i class="bi bi-search fs-5 cursor-pointer" style="cursor: pointer; color: #F5A3B0;"></i>
            
            <!-- Simulasi guest (karena tidak ada session di frontend static) -->
            <div class="dropdown d-inline-block">
                <a href="login.php" class="btn rounded-pill px-3 me-2 fw-bold" style="background: #F5A3B0; color: white; border: none;">Masuk</a>
                <a href="register.php" class="btn rounded-pill px-3 fw-bold" style="border: 2px solid #F5A3B0; color: #F5A3B0;">Daftar</a>
            </div>
        </div>
    </div>
</nav>

<section class="hero">
    <div class="container hero-content">
        <div class="row justify-content-center text-center">
            <div class="col-lg-10">
                <h1>Temukan makanan favoritmu!</h1>
                <p class="lead">
                    Jogja Foodies adalah platform kuliner digital yang dirancang khusus untuk membantu para pecinta makanan menemukan cita rasa autentik Yogyakarta. Website ini menyajikan berbagai rekomendasi makanan khas Jogja mulai dari gudeg legendaris, bakpia, sate klathak, hingga wedang uwuh, lengkap dengan informasi rating, lokasi, harga, dan deskripsi singkat. Dengan tampilan yang simpel dan warna pastel yang menenangkan, Jogja Foodies mengajak Anda menjelajahi kuliner Jogja dengan mudah, cepat, dan menyenangkan. Temukan makanan favoritmu dan mulai petualangan kuliner Jogja sekarang juga!
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Top Rated Section - Data makanan khas Jogja dengan warna pastel -->
<section class="top-rated-section">
    <div class="container">
        <h2 class="section-title"> Top Rated <span style="color: #F5A3B0;">Dishes</span></h2>
        <p class="section-subtitle">Highest rated foods by our foodies community</p>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="top-card">
                    <div class="rank rank-1">
                        <i class="fas fa-crown"></i>
                    </div>
                    <h4 class="fw-bold mt-3">Gudeg </h4>
                    <div class="rating mb-2">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        <span>(4.9)</span>
                    </div>
                    <p class="text-muted">Nangka muda dimasak dengan santan kental, gula aren, dan rempah khas - manis legit legendaris.</p>
                    <div class="location">
                        <i class="fas fa-map-marker-alt"></i> Kawasan Wijilan, Yogyakarta
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="top-card">
                    <div class="rank rank-3">
                        <i class="fas fa-award"></i>
                    </div>
                    <h4 class="fw-bold mt-3">Ayam Bakar Artomoro</h4>
                    <div class="rating mb-2">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        <span>(4.9)</span>
                    </div>
                    <p class="text-muted">kuliner legendaris di Yogyakarta yang terkenal dengan ayam kampung pedas manis berukuran besar, bumbu meresap hingga ke tulang, dan proses masak 5 jam.</p>
                    <div class="location">
                        <i class="fas fa-map-marker-alt"></i> Jl. Palagan Tentara Pelajar Km.7,8 No.30s, Karang Moko, Sariharjo, Sleman, Yogyakarta.
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="top-card">
                    <div class="rank rank-2">
                        <i class="fas fa-medal"></i>
                    </div>
                    <h4 class="fw-bold mt-3">Bakpia </h4>
                    <div class="rating mb-2">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                        <span>(4.8)</span>
                    </div>
                    <p class="text-muted">Kue khas berbentuk bulat pipih dengan isian kacang hijau manis, tekstur lembut dan legit.</p>
                    <div class="location">
                        <i class="fas fa-map-marker-alt"></i> Jalan Pathuk, Jogja
                    </div>
                </div>
            </div>


        </div>
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