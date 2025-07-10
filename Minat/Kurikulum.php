<?php
session_start();

// Menentukan apakah pengguna sudah login
$userLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == true;
// Menyimpan status tema jika pengguna mengubahnya
if (isset($_POST['theme-toggle'])) {
    $_SESSION['theme'] = ($_SESSION['theme'] == 'dark') ? 'light' : 'dark';
}

// Menentukan class CSS berdasarkan tema
$themeClass = isset($_SESSION['theme']) && $_SESSION['theme'] == 'dark' ? 'bg-dark text-light dark-mode' : '';
$themeIcon = isset($_SESSION['theme']) && $_SESSION['theme'] == 'dark' ? 'fas fa-sun' : 'fas fa-moon';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="logo.png" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK - Sistem Pendukung Keputusan</title>
    
    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <style>
        body::-webkit-scrollbar {
            display: none;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .navbar {
            background: linear-gradient(to right, #561C24, #6D2932);
        }
        .hero, .section-explore {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 50px;
            flex-wrap: nowrap;
        }
        .hero-text, .section-explore-text {
            max-width: 600px;
        }
        .hero img, .section-explore img {
            width: 80%;
            max-width: 350px;
        }
        .section-explore {
            background-color: #E8D8C4;
            color: #561C24;
            gap: 40px;
        }
        .btn-gradient {
            background: linear-gradient(to right, #561C24, #6D2932);
            color: white;
            border: none;
            transition: background 0.3s, transform 0.2s;
        }
        .btn-gradient:hover {
            background: linear-gradient(to right, #C7B7A3, #E8D8C4);
            transform: translateY(-2px);
        }
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
        .custom-title {
            margin-top: 20px;
        }
        .dark-mode .table {
    background-color: #333;
    color: white;
}

.dark-mode .table th,
.dark-mode .table td {
    background-color: #444;
    color: white;
    border-color: #555;
}

    </style>
</head>
<body class="<?php echo $themeClass; ?>">

    <!-- Navbar -->
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
    <?php endif; ?>                </ul>
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

    <!-- Konten Daftar Mata Kuliah -->
    <div class="container">
        <h4 class="text-center mb-4 custom-title">
            <i class="bi bi-back"></i> 
            Daftar Mata Kuliah
        </h4>
        <div id="data-container"></div>
    </div>

    <!-- Script untuk Fetch Data -->
    <script>
        fetch('getData.php')
            .then(response => response.json())
            .then(data => {
                let container = document.getElementById("data-container");
                let groupedData = {};

                data.forEach(item => {
                    if (!groupedData[item.semester]) {
                        groupedData[item.semester] = [];
                    }
                    groupedData[item.semester].push(item);
                });

                Object.keys(groupedData).forEach(semester => {
                    let tableRows = groupedData[semester].map(item => `
                        <tr>
                            <td>${item.kode_mk}</td>
                            <td>${item.mata_kuliah}</td>
                            <td>${item.sks}</td>
                            <td>${item.status}</td>
                        </tr>
                    `).join('');

                    let card = `
                        <div class="card mb-4">
                            <div class="card-header">
                                Semester ${semester}
                            </div>
                            <div class="card-body">
<table class="table table-bordered table-striped <?= $themeClass ? 'table-dark' : '' ?>">

                                        <thead class="table-danger">
                                            <tr>
                                                <th>Kode MK</th>
                                                <th>Mata Kuliah</th>
                                                <th>SKS</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${tableRows}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    `;

                    container.innerHTML += card;
                });
            })
            .catch(error => console.error('Error:', error));
    </script>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 DSS-TI | Sistem Pendukung Keputusan untuk Pemilihan Peminatan</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
