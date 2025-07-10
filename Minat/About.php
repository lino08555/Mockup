<?php
session_start();

// Menentukan apakah pengguna sudah login
$userLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == true;

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

/* Gaya untuk bagian hero */
.hero {
    display: flex; /* Menggunakan flexbox untuk tata letak */
    align-items: center; /* Menyelaraskan item secara vertikal */
    justify-content: space-between; /* Mengatur jarak antar item */
    padding: 50px; /* Memberikan padding */
    flex-wrap: nowrap; /* Menghindari pembungkusan item */
}
.hero-text {
    max-width: 600px; /* Membatasi lebar teks hero */
    color: #561C24; /* Warna teks hero */
}
.hero img {
    width: 80%; /* Mengatur lebar gambar */
    max-width: 350px; /* Memastikan lebar maksimum gambar */
    order: 2; /* Menentukan urutan tampilan gambar */
}

/* Gaya untuk bagian eksplorasi */
.section-explore {
    background-color: #E8D8C4; /* Mengatur latar belakang */
    color: #561C24; /* Mengatur warna teks */
    padding: 50px; /* Memberikan padding */
    display: flex; /* Menggunakan flexbox untuk tata letak */
    align-items: center; /* Menyelaraskan item secara vertikal */
    justify-content: space-between; /* Mengatur jarak antar item */
    flex-wrap: nowrap; /* Menghindari pembungkusan item */
    gap: 40px; /* Memberikan jarak antar elemen */
}
.section-explore-text {
    max-width: 600px; /* Membatasi lebar teks eksplorasi */
}
.section-explore img {
    width: 100%; /* Mengatur lebar gambar */
    max-width: 350px; /* Memastikan lebar maksimum gambar */
    margin: auto; /* Memindahkan gambar lebih ke kanan */
}

/* Gaya untuk tombol dengan latar belakang gradasi merah */
.btn-gradient {
    background: linear-gradient(to right, #561C24, #6D2932); /* Gradasi merah */
    color: white; /* Warna teks putih */
    border: none; /* Menghilangkan border */
    transition: background 0.3s, transform 0.2s; /* Efek transisi */
}
.btn-gradient:hover {
    background: linear-gradient(to right, #C7B7A3, #E8D8C4); /* Gradasi saat hover */
    transform: translateY(-2px); /* Efek mengangkat saat hover */
}

/* Gaya untuk footer agar tetap terlihat pada mode dark */
.footer {
    background-color: #f8f9fa; /* Warna latar belakang footer */
    padding: 20px; /* Memberikan padding */
    text-align: center; /* Mengatur teks agar berada di tengah */
    margin-top: 50px; /* Memberikan jarak atas */
}

/* Gaya footer di mode dark */
.dark-mode .footer {
    background-color: #222; /* Warna latar belakang footer di mode dark */
    color: white; /* Warna teks footer di mode dark */
}

/* Menambahkan aturan untuk mode terang */
.dark-mode {
    background-color: #222; /* Latar belakang gelap */
    color: white; /* Teks terang */
}

.dark-mode .navbar, .dark-mode .hero-text, .dark-mode .section-explore-text {
    color: white; /* Warna teks terang di navbar, hero, dan section eksplorasi */
}

.dark-mode .section-explore {
    background-color: #333; /* Latar belakang eksplorasi lebih gelap di mode dark */
}
    </style>
</head>
<body class="<?php echo $themeClass; ?>">
    <!-- Navigasi -->
     <!-- Navigasi -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
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
                    <li class="nav-item"><a class="nav-link text-white" href="about.php">Tentang Kami</a></li>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <li class="nav-item"><a class="nav-link text-white" href="kriteria.php">Kriteria</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="alternatif.php">Alternatif</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="matakuliah.php">Mata Kuliah</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="kri.php">Bobot Alternatif</a></li>
    <?php endif; ?>
                </ul>
                <ul class="navbar-nav"> <!-- Menu login/logout -->
                    <?php if ($userLoggedIn): ?>
<li class="nav-item">
    <button class="btn btn-outline-light me-2" data-bs-toggle="modal" data-bs-target="#userProfileModal">
        <i class="fas fa-user"></i> Profil
    </button>
</li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light me-2" href="proses_logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    <?php else: ?>
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
                    <?php endif; ?>
                    <li class="nav-item">
                        <form method="POST" action=""> <!-- Form untuk mengganti tema -->
                            <button class="btn btn-outline-light" name="theme-toggle"><i class="fas fa-moon"></i></button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Bagian hero -->
    <div class="container hero">
        <div class="hero-text">
            <h1>Tentang Kami!</h1>
            <p>Kami adalah platform yang didedikasikan untuk membantu mahasiswa dalam memilih peminatan 
                yang sesuai dengan minat dan nilai mereka. Melalui tes interaktif yang disediakan, kami 
                memungkinkan mahasiswa untuk mengevaluasi minat dan capaian akademis mereka dari semester 
                1 hingga semester 4.</p>
            <p>Berdasarkan hasil tes tersebut, sistem kami menggunakan metode <strong>Simple Additive 
                Weighting (SAW)</strong> untuk memberikan rekomendasi peminatan yang paling sesuai.</p>
            <p>Setelah menerima rekomendasi, mahasiswa dapat mengakses roadmap yang terperinci, yang 
                menampilkan mata kuliah yang akan diambil dalam peminatan yang dipilih. Kami percaya 
                bahwa pemilihan peminatan yang tepat adalah langkah penting dalam perjalanan akademik 
                seseorang, dan kami berkomitmen untuk menyediakan dukungan komprehensif dalam proses ini.
            </p>
            <a href="index.php" class="btn btn-outline-danger">
                <i class="fas fa-search"></i> Explore Peminatan →
            </a>
        </div>
        <img src="image1.png" alt="Ilustrasi SPK"> <!-- Gambar ilustrasi -->
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
            document.body.classList.toggle('bg-dark'); // Mengubah latar belakang
            document.body.classList.toggle('text-light'); // Mengubah warna teks
            document.body.classList.toggle('dark-mode'); // Menambahkan kelas dark-mode
            this.innerHTML = document.body.classList.contains('bg-dark') ? 
                '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>'; // Mengubah ikon tombol
        });
    </script>

    <!-- Modal untuk Profil Pengguna -->
<div class="modal fade" id="userProfileModal" tabindex="-1" aria-labelledby="userProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userProfileModalLabel">Profil Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Nama:</strong> <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Belum Login'; ?></p>
                <p><strong>Email:</strong> <?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Belum Login'; ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>