<?php
session_start();

// Cek login
$userLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == true;

// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db = "spk_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Atur tema
if (isset($_POST['theme-toggle'])) {
    $_SESSION['theme'] = ($_SESSION['theme'] == 'dark') ? 'light' : 'dark';
} elseif (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
}

$themeClass = $_SESSION['theme'] == 'dark' ? 'bg-dark text-light dark-mode' : '';
$themeIcon = $_SESSION['theme'] == 'dark' ? 'fas fa-sun' : 'fas fa-moon';

// Pagination
$limit = 5; // data per halaman
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Hitung total data
$totalSql = "SELECT COUNT(*) as total FROM akademik";
$totalResult = $conn->query($totalSql);
$totalRow = $totalResult->fetch_assoc();
$totalData = $totalRow['total'];
$totalPages = ceil($totalData / $limit);

// Query data dengan limit dan offset
$sql = "SELECT * FROM akademik LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="logo.png" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peminatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
    .navbar {
        background: linear-gradient(to right, #561C24, #6D2932);
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    .navbar .nav-link {
        color: white !important;
    }
    .navbar .nav-link:hover {
        color: #E8D8C4 !important;
    }
    .navbar-brand {
        color: white !important;
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

    .dark-mode .footer {
        background-color: #222;
        color: #ccc;
    }

    body, .table, .navbar, .footer {
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    </style>
</head>
<body class="<?php echo $themeClass; ?>">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light">
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
                <li class="nav-item"><a class="nav-link text-white" href="about.php">Tentang Kami</a></li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link text-white" href="kriteria.php">Kriteria</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="alternatif.php">Alternatif</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="matakuliah.php">Mata Kuliah</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="kri.php">Bobot Alternatif</a></li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if ($userLoggedIn): ?>
                    <li class="nav-item">
                        <a class="btn btn-outline-light me-2" href="profile.php">
                            <i class="fas fa-user"></i> Profil
                        </a>
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
                    <form method="POST" action="">
                        <button class="btn btn-outline-light" name="theme-toggle">
                            <i class="<?php echo $themeIcon; ?>"></i>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Konten -->
<div class="container" style="max-width: 1050px; margin: auto; font-size: 0.85rem;">
    <h2 class="my-4">Data Mata Kuliah</h2>
    <a href="create_akademik.php" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Tambah Data</a>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Kode Mata Kuliah</th>
                    <th>Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Peminatan</th>
                    <th>Semester</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".htmlspecialchars($row['kode_mk'])."</td>";
                    echo "<td>".htmlspecialchars($row['mata_kuliah'])."</td>";
                    echo "<td>".htmlspecialchars($row['sks'])."</td>";

                    // Tampilkan peminatan atau "-"
                    $peminatan = trim($row['peminatan']);
                    echo "<td class='text-center'>".($peminatan ? htmlspecialchars($peminatan) : '-')."</td>";

                    echo "<td>".htmlspecialchars($row['semester'])."</td>";
                    echo "<td>".htmlspecialchars($row['status'])."</td>";
                    echo "<td>
                            <a href='edit_akademik.php?id=".$row['id']."' class='btn btn-warning btn-sm'><i class='fas fa-edit'></i> Edit</a>
                            <a href='delete_akademik.php?id=".$row['id']."' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin ingin menghapus data ini?');\"><i class='fas fa-trash'></i> Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>Tidak ada data.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $page-1; ?>">Previous</a></li>
            <?php else: ?>
                <li class="page-item disabled"><span class="page-link">Previous</span></li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $page+1; ?>">Next</a></li>
            <?php else: ?>
                <li class="page-item disabled"><span class="page-link">Next</span></li>
            <?php endif; ?>
        </ul>
    </nav>

</div>


<?php $conn->close(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
