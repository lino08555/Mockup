<?php
include 'koneksi.php';
session_start();

// Cek role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$nama = '';
$edit_mode = false;
$id_edit = '';

// Tambah data
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    mysqli_query($conn, "INSERT INTO alternatif (nama) VALUES ('$nama')");
    header("Location: alternatif.php");
    exit;
}

// Edit data
if (isset($_GET['edit'])) {
    $id_edit = (int)$_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM alternatif WHERE id_alternatif = $id_edit");
    if ($row = mysqli_fetch_assoc($result)) {
        $nama = $row['nama'];
        $edit_mode = true;
    }
}

// Update data
if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    mysqli_query($conn, "UPDATE alternatif SET nama = '$nama' WHERE id_alternatif = $id");
    header("Location: alternatif.php");
    exit;
}

// Hapus data + relasi terkait
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];

    // Hapus data relasi di tabel lain yang terkait, contoh:
    mysqli_query($conn, "DELETE FROM bobot_alternatif WHERE id_alternatif = $id");
    mysqli_query($conn, "DELETE FROM nilai_alternatif WHERE id_alternatif = $id");
    // Hapus data utama
    mysqli_query($conn, "DELETE FROM alternatif WHERE id_alternatif = $id");

    echo "<script>alert('Data dan relasi terkait berhasil dihapus'); window.location='alternatif.php';</script>";
    exit;
}

// Ambil semua data alternatif
$alternatif = mysqli_query($conn, "SELECT * FROM alternatif ORDER BY id_alternatif ASC");

// Atur tema
$userLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == true;
if (isset($_POST['theme-toggle'])) {
    $_SESSION['theme'] = ($_SESSION['theme'] == 'dark') ? 'light' : 'dark';
}
$themeClass = isset($_SESSION['theme']) && $_SESSION['theme'] == 'dark' ? 'bg-dark text-light dark-mode' : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Alternatif</title>
    <link rel="icon" href="logo.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .dark-mode .table { background-color: #333; color: white; }
        .dark-mode .table th, .dark-mode .table td { background-color: #444; color: white; border-color: #555; }
        body::-webkit-scrollbar { display: none; overflow-x: hidden; }
        .navbar { background: linear-gradient(to right, #561C24, #6D2932); }
        .btn-gradient { background: linear-gradient(to right, #561C24, #6D2932); color: white; border: none; }
        .btn-gradient:hover { background: linear-gradient(to right, #C7B7A3, #E8D8C4); transform: translateY(-2px); }
        .dark-mode { background-color: #222; color: white; }
        .dark-mode .navbar, .dark-mode .section-explore-text, .dark-mode .table, .dark-mode .form-control { color: white; background-color: #333; border: 1px solid #ccc; }
    </style>
</head>
<body class="<?= $themeClass ?>">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand text-white fw-bold d-flex align-items-center" href="#">
            <img src="logo.png" width="40" height="40"> <span class="ms-2">DSS-TI</span>
        </a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link text-white" href="index.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="kurikulum.php">Kurikulum</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="about.php">Tentang Kami</a></li>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link text-white" href="kriteria.php">Kriteria</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="alternatif.php">Alternatif</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="matakuliah.php">Mata Kuliah</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="kri.php">Bobot Alternatif</a></li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if ($userLoggedIn): ?>
                    <li class="nav-item">
                        <button class="btn btn-outline-light me-2" data-bs-toggle="modal" data-bs-target="#userProfileModal">
                            <i class="fas fa-user"></i> Profil
                        </button>
                    </li>
                    <li class="nav-item"><a class="btn btn-outline-light me-2" href="proses_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="btn btn-outline-light me-2" href="Login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <li class="nav-item"><a class="btn btn-outline-light me-2" href="Register.php"><i class="fas fa-user-plus"></i> Register</a></li>
                <?php endif; ?>
                <li class="nav-item">
                    <form method="POST"><button class="btn btn-outline-light" name="theme-toggle"><i class="fas fa-moon"></i></button></form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="mb-4">Data Alternatif</h2>
    <button class="btn btn-success mb-3" onclick="toggleForm()" id="toggleButton">
        <?= $edit_mode ? 'Sembunyikan Form' : 'Tambah Alternatif' ?>
    </button>

    <div class="card mb-4" id="formCard" style="display: <?= $edit_mode ? 'block' : 'none' ?>;">
        <div class="card-body">
            <form method="POST">
                <?php if ($edit_mode): ?>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($id_edit) ?>">
                <?php endif; ?>
                <div class="form-group mb-3">
                    <input type="text" name="nama" class="form-control" placeholder="Nama Alternatif" value="<?= htmlspecialchars($nama) ?>" required>
                </div>
                <button type="submit" name="<?= $edit_mode ? 'update' : 'tambah' ?>" class="btn btn-<?= $edit_mode ? 'warning' : 'primary' ?>">
                    <?= $edit_mode ? 'Simpan Perubahan' : 'Tambah' ?>
                </button>
                <?php if ($edit_mode): ?>
                    <a href="alternatif.php" class="btn btn-secondary ms-2">Batal</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <table class="table table-bordered table-striped <?= $themeClass ? 'table-dark' : '' ?>">
        <thead class="table-dark">
            <tr>
                <th width="10%">ID</th>
                <th>Nama Alternatif</th>
                <th width="25%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($alternatif)): ?>
                <tr>
                    <td><?= $row['id_alternatif'] ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td>
                        <a href="alternatif.php?edit=<?= $row['id_alternatif'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                        <a href="alternatif.php?hapus=<?= $row['id_alternatif'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')"><i class="fas fa-trash"></i> Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleForm() {
    const form = document.getElementById('formCard');
    const btn = document.getElementById('toggleButton');
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
        btn.innerText = 'Sembunyikan Form';
    } else {
        form.style.display = 'none';
        btn.innerText = 'Tambah Alternatif';
    }
}
</script>

<div class="modal fade" id="userProfileModal" tabindex="-1" aria-labelledby="userProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userProfileModalLabel">Profil Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Nama:</strong> <?= isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Belum Login' ?></p>
                <p><strong>Email:</strong> <?= isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Belum Login' ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
