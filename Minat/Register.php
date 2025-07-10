<?php
session_start();

// Menyimpan status tema jika pengguna mengubahnya
if (isset($_POST['theme-toggle'])) {
    $_SESSION['theme'] = ($_SESSION['theme'] == 'dark') ? 'light' : 'dark';
}

// Mengatur tema default berdasarkan preferensi pengguna
$themeClass = isset($_SESSION['theme']) && $_SESSION['theme'] == 'dark' ? 'bg-dark text-light dark-mode' : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="logo.png" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK - Sistem Pendukung Keputusan</title>
    
    <!-- Memuat Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        /* Gaya umum untuk seluruh halaman */
        body::-webkit-scrollbar {
            display: none; /* Menyembunyikan scrollbar */
            font-family: Arial, sans-serif;
            overflow-x: hidden; /* Menyembunyikan overflow horizontal */
        }
        
        /* Gaya untuk navbar dengan latar belakang gradasi merah */
        .navbar {
            background: linear-gradient(to right, #561C24, #6D2932); /* Gradasi merah */
        }

        /* Gaya untuk tema gelap */
        .dark-mode .navbar {
            background: linear-gradient(to right, #561C24, #6D2932); /* Gradasi merah */
        }
        .dark-mode {
            background-color: #222; /* Warna latar belakang untuk mode gelap */
            color: white; /* Warna teks di mode gelap */
        }
        .dark-mode .footer {
            background-color: #111; /* Warna footer di mode gelap */
            color: white; /* Warna teks footer di mode gelap */
        }

        /* Gaya untuk bagian registrasi */
        .register-container {
            background: #E8D8C4; /* Warna latar belakang */
            padding: 30px; /* Memberikan padding */
            border-radius: 10px; /* Sudut melengkung */
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* Bayangan */
            width: 100%; /* Lebar penuh */
            max-width: 450px; /* Lebar maksimum */
            text-align: center; /* Teks di tengah */
            margin: auto; /* Menjaga di tengah */
            margin-top: 30px; /* Jarak atas */
        }
        .form-control {
            border-radius: 5px; /* Sudut melengkung untuk input */
        }
        .btn-register {
            background-color: #6D2932; /* Warna latar belakang tombol */
            color: white; /* Warna teks tombol */
            width: 100%; /* Lebar penuh */
            border-radius: 5px; /* Sudut melengkung */
        }
        .sign-in-link {
            font-size: 14px; /* Ukuran font */
        }

        /* Gaya untuk tema gelap */
        .dark-mode .register-container {
            background: #343a40; /* Warna latar belakang untuk tema gelap */
            color: white; /* Warna teks untuk tema gelap */
        }
        .dark-mode .form-control {
            background: #495057; /* Warna latar belakang input */
            color: white; /* Warna teks input */
        }
        .dark-mode .btn-register {
            background-color: #6D2932; /* Warna tetap untuk tombol */
        }
        .dark-mode .sign-in-link {
            color: #adb5bd; /* Warna teks untuk tautan di tema gelap */
        }

        /* Gaya untuk footer */
        .footer {
            background-color: #f8f9fa; /* Warna latar belakang footer */
            padding: 20px; /* Memberikan padding */
            text-align: center; /* Mengatur teks agar berada di tengah */
            margin-top: 50px; /* Memberikan jarak atas */
            margin-bottom: 20px; /* Menambahkan margin bawah untuk jarak lebih */
        }
    </style>
</head>
<body class="<?php echo $themeClass; ?>">
    <!-- Navigasi -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand text-white fw-bold d-flex align-items-center" href="#">
                <img src="logo.png" alt="Logo" width="40" height="40" class="d-inline-block"> <!-- Logo -->
                <span class="ms-2">DSS-TI</span> <!-- Nama aplikasi -->
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span> <!-- Tombol untuk menu responsif -->
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto"> <!-- Menu navigasi -->
                    <li class="nav-item"><a class="nav-link text-white" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="kurikulum.php">Kurikulum</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="about.php">About</a></li>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <li class="nav-item"><a class="nav-link text-white" href="kriteria.php">Kriteria</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="alternatif.php">Alternatif</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="kri.php">Bobot Alternatif</a></li>
    <?php endif; ?>
                </ul>
                <ul class="navbar-nav"> <!-- Menu untuk login dan pengaturan tema -->
                    <li class="nav-item">
                        <a class="btn btn-outline-light me-2" href="Login.php">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light me-2" href="Register.php">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action=""> <!-- Form untuk mengganti tema -->
                            <button class="btn btn-outline-light" name="theme-toggle" id="theme-toggle"><i class="fas fa-moon"></i></button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Register Form -->
    <div class="register-container">
        <h4><strong>Register</strong></h4>
<!-- Tambahkan ke bagian dalam <form> sebelum tombol submit -->
<form action="proses_register.php" method="POST">
    <div class="row g-2 mb-3">
        <div class="col">
            <label class="form-label">First Name*</label>
            <input type="text" class="form-control" name="first_name" required>
        </div>
        <div class="col">
            <label class="form-label">Last Name*</label>
            <input type="text" class="form-control" name="last_name" required>
        </div>
    </div>
    <div class="mb-3 text-start">
        <label class="form-label">NIM*</label>
        <input type="text" class="form-control" name="nim" required>
    </div>
    <div class="mb-3 text-start">
        <label class="form-label">Email*</label>
        <input type="email" class="form-control" name="email" required>
    </div>
    <div class="mb-3 text-start">
        <label class="form-label">Password*</label>
        <input type="password" class="form-control" name="password" required>
    </div>
    <div class="mb-3 text-start">
        <label class="form-label">Confirm Password*</label>
        <input type="password" class="form-control" name="confirm_password" required>
    </div>
    <div class="mb-3 text-start">
        <label class="form-label">Role*</label>
        <select class="form-control" name="role" required>
            <option value="">-- Pilih Role --</option>
            <option value="user">Mahasiswa</option>
        </select>
    </div>
    <button type="submit" class="btn btn-register">Register</button>
</form>

        <p class="sign-in-link mt-3">Already have an account? <a href="login.php">Sign in</a></p>
    </div>
  
    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 DSS-TI | Sistem Pendukung Keputusan untuk Pemilihan Peminatan</p>
    </footer>

    <!-- Memuat Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Tombol untuk mengubah tema (dark/light mode)
        document.getElementById('theme-toggle').addEventListener('click', function () {
            document.body.classList.toggle('bg-dark');
            document.body.classList.toggle('text-light');
            document.body.classList.toggle('dark-mode'); // Menambahkan kelas dark-mode
            this.innerHTML = document.body.classList.contains('bg-dark') ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
        });
    </script>
</body>
</html>