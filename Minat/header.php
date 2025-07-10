<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK - Sistem Pendukung Keputusan</title>
    
    <!-- Memuat Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        /* Gaya umum untuk seluruh halaman */
        body::-webkit-scrollbar {
            display: none;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
        }
        
        /* Gaya untuk footer agar tetap terlihat pada mode dark */
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            margin-top: 50px;
        }

        .dark-mode .footer {
            background-color: #222;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navigasi -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand text-primary fw-bold d-flex align-items-center" href="#">
                <img src="logo.png" alt="Logo" width="40" height="40" class="d-inline-block">
                <span class="ms-2">DSS-TI</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="kurikulum.php">Kurikulum</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="btn btn-outline-secondary me-2" href="Login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <li class="nav-item"><a class="btn btn-primary me-2" href="Register.php"><i class="fas fa-user-plus"></i> Register</a></li>
                    <li class="nav-item">
                        <button class="btn btn-outline-dark" id="theme-toggle"><i class="fas fa-moon"></i></button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
