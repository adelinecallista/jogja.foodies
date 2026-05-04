<!DOCTYPE html>
<html lang="en">
<head>
<title>Login</title>
  <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='85'>🍽️</text></svg>">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="bootstrap.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
  <div class="container">
    <a class="navbar-brand" href="#">
  <img src="logo.jpeg" alt="Logo" height="35" class="d-inline-block align-middle me-2">
  JOGJA.<span>FOODIES</span>
</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="home.html">HOME</a></li>
        <li class="nav-item"><a class="nav-link" href="menu.html">MENU</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php">LOGIN</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="login-section">
  <div class="login-card">
    <h2>Login to Your <span>Account</span></h2>
    <form method="POST" action="prosesLogin.php">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>
      
       <?php
      session_start();
      if (isset($_SESSION['login_error'])) {
          echo "<p class='error-message'>".$_SESSION['login_error']."</p>";
          unset($_SESSION['login_error']);
      }
      ?>

      <button type="submit" name="login" class="login-btn">Login</button>
    </form>
    <div class="login-footer">
    </div>
  </div>
</div>
</body>
</html>