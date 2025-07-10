<?php
session_start();

// Toggle tema
if (isset($_POST['theme-toggle'])) {
    $_SESSION['theme'] = (isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark') ? 'light' : 'dark';
    header("Location: login.php");
    exit();
}

$themeClass = isset($_SESSION['theme']) && $_SESSION['theme'] == 'dark' ? 'bg-dark text-light dark-mode' : '';
$themeIcon = isset($_SESSION['theme']) && $_SESSION['theme'] == 'dark' ? 'fa-sun' : 'fa-moon';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="logo.png" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK - Sistem Pendukung Keputusan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body::-webkit-scrollbar { display: none; font-family: Arial, sans-serif; overflow-x: hidden; }
        .navbar { background: linear-gradient(to right, #561C24, #6D2932); }
        .login-container {
            background: #E8D8C4; padding: 30px; border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 400px; margin: 30px auto; text-align: center;
        }
        .form-control { border-radius: 5px; }
        .btn-login { background-color: #6D2932; color: white; width: 100%; border-radius: 5px; }
        .forgot-password { text-align: right; font-size: 14px; display: block; }
        .or-divider { display: flex; align-items: center; text-align: center; margin: 20px 0; }
        .or-divider::before, .or-divider::after { content: ""; flex: 1; border-bottom: 1px solid #ddd; }
        .or-divider span { padding: 0 10px; color: #6c757d; font-size: 14px; }
        .btn-google { background: white; border: 1px solid #ddd; width: 100%; border-radius: 5px; }
        .btn-google img { width: 20px; margin-right: 10px; }
        .sign-up-link { font-size: 14px; }
        .dark-mode .login-container { background: #343a40; color: white; }
        .dark-mode .form-control { background: #495057; color: white; }
        .dark-mode .btn-login { background-color: #6D2932; }
        .dark-mode .forgot-password, .dark-mode .or-divider span, .dark-mode .sign-up-link { color: #adb5bd; }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; margin-top: 50px; margin-bottom: 20px; }
        .dark-mode .footer { background-color: #222; color: white; }
        .password-toggle {
            position: absolute; right: 15px; top: 50%; transform: translateY(-50%);
            cursor: pointer; color: #888;
        }
    </style>
</head>
<body class="<?= $themeClass ?>">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand text-white fw-bold d-flex align-items-center" href="#">
            <img src="logo.png" alt="Logo" width="40" height="40" class="d-inline-block">
            <span class="ms-2">DSS-TI</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link text-white" href="index.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="kurikulum.php">Kurikulum</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="about.php">About</a></li>
 <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <li class="nav-item"><a class="nav-link text-white" href="kriteria.php">Kriteria</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="alternatif.php">Alternatif</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="kri.php">Bobot Alternatif</a></li>
    <?php endif; ?>            </ul>
            <ul class="navbar-nav">
                <?php if (!isset($_SESSION['id_user'])): ?>
                    <li class="nav-item"><a class="btn btn-outline-light me-2" href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <li class="nav-item"><a class="btn btn-outline-light me-2" href="register.php"><i class="fas fa-user-plus"></i> Register</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="btn btn-outline-light me-2" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php endif; ?>
                <li class="nav-item">
                    <form method="POST" action="">
                        <button class="btn btn-outline-light" name="theme-toggle"><i class="fas <?= $themeIcon ?>"></i></button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Login Form -->
<div class="login-container">
    <h4><strong>Sign in to your account</strong></h4>

    <!-- ALERT -->
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger mt-3">
            <?= ($_GET['error'] == 'wrong_password') ? 'Password salah!' : (($_GET['error'] == 'email_not_found') ? 'Email tidak ditemukan!' : '') ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="proses_login.php">
        <div class="mb-3 text-start">
            <label for="email" class="form-label">Email*</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="example@student.ums.ac.id" required>
        </div>

        <div class="mb-3 text-start position-relative">
            <label for="password" class="form-label">Password*</label>
            <input type="password" class="form-control" id="password" name="password" required>
            <i class="fas fa-eye password-toggle" id="togglePassword"></i>
            <a href="#" class="forgot-password">Forgot Password?</a>
        </div>
                <div class="mb-3 text-start">
            <label for="role" class="form-label">Role*</label>
            <select class="form-control" id="role" name="role" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin">Admin</option>
                <option value="mahasiswa">Mahasiswa</option>
            </select>
        </div>

        <button type="submit" class="btn btn-login">Login</button>
    </form>

    <div class="or-divider"><span>or sign in with</span></div>
    <button class="btn btn-google">
        <img src="https://img.icons8.com/color/48/000000/google-logo.png" alt="Google Icon"> Sign in with Google
    </button>
    <p class="sign-up-link mt-3">Don't have an account? <a href="register.php">Sign up</a></p>
</div>

<!-- Footer -->
<footer class="footer">
    <p>&copy; 2025 DSS-TI | Sistem Pendukung Keputusan untuk Pemilihan Peminatan</p>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");

    togglePassword.addEventListener("click", function () {
        const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
        passwordInput.setAttribute("type", type);
        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
    });
</script>

</body>
</html>
