<?php
session_start();
include 'koneksi.php'; // Pastikan file koneksi.php ada dan berisi $conn

$edit_mode = false;
$id_kriteria = $kode = $nama_kriteria = $jenis = "";
$bobot_alternatif_values = []; // Untuk menyimpan bobot alternatif saat edit

// Variabel untuk menyimpan hasil perhitungan bobot alternatif dari form
$sum_bobot_alternatif = null;
$input_kode = '';
$input_nama_kriteria = '';
$input_jenis = '';

// Flag untuk menandakan apakah modal hasil perhitungan harus ditampilkan
$showResultModal = false;
$modalTitle = ''; // Akan digunakan untuk judul modal
$modalMessage = ''; // Akan digunakan untuk pesan di modal

// Menentukan apakah pengguna sudah login
$userLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;

// Menyimpan status tema jika pengguna mengubahnya
if (isset($_POST['theme-toggle'])) {
    $_SESSION['theme'] = ($_SESSION['theme'] === 'dark') ? 'light' : 'dark';
}

// Mengatur tema default berdasarkan preferensi pengguna
$themeClass = (isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark') ? 'bg-dark text-light dark-mode' : '';

// --- Ambil semua alternatif unik ---
$alternatif_for_input = [];
$alt_query = "SELECT DISTINCT id_alternatif, nama FROM alternatif ORDER BY id_alternatif ASC";
$result_alt = mysqli_query($conn, $alt_query);

while ($row = mysqli_fetch_assoc($result_alt)) {
    $alternatif_for_input[] = $row;
}
// --- END Ambil Alternatif ---

// --- Proses Tambah Kriteria ---
if (isset($_POST['tambah'])) {
    $input_kode = $_POST['kode'];
    $input_nama_kriteria = $_POST['nama_kriteria'];
    $input_jenis = $_POST['jenis'];
    $bobot_alternatif_input = isset($_POST['bobot_alternatif']) ? $_POST['bobot_alternatif'] : [];

    // --- Validasi Kode Kriteria Duplikat ---
    $check_kode_stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM kriteria WHERE kode = ?");
    if (!$check_kode_stmt) {
        echo "<script>alert('Gagal menyiapkan query cek kode duplikat: " . mysqli_error($conn) . "'); window.location.href='kriteria.php';</script>";
        exit;
    }
    mysqli_stmt_bind_param($check_kode_stmt, "s", $input_kode);
    mysqli_stmt_execute($check_kode_stmt);
    mysqli_stmt_bind_result($check_kode_stmt, $count_kode);
    mysqli_stmt_fetch($check_kode_stmt);
    mysqli_stmt_close($check_kode_stmt);

    if ($count_kode > 0) {
        echo "<script>alert('Kesalahan: Kode kriteria " . htmlspecialchars($input_kode) . " sudah ada. Mohon gunakan kode lain.'); window.location.href='kriteria.php';</script>";
        exit;
    }

    // Mulai transaksi untuk integritas data
    mysqli_begin_transaction($conn);
    try {
        // 1. Insert ke tabel kriteria
        $query_insert_kriteria = "INSERT INTO kriteria (kode, nama_kriteria, jenis) VALUES (?, ?, ?)";
        $stmt_kriteria = mysqli_prepare($conn, $query_insert_kriteria);
        if (!$stmt_kriteria) {
            throw new Exception("Gagal menyiapkan query insert kriteria: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt_kriteria, "sss", $input_kode, $input_nama_kriteria, $input_jenis);
        mysqli_stmt_execute($stmt_kriteria);
        $id_kriteria_baru = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt_kriteria);

        // 2. Insert bobot alternatif
        $current_sum_for_modal = 0;
        foreach ($alternatif_for_input as $alt) {
            $id_alternatif = $alt['id_alternatif'];
            $nilai_bobot = isset($bobot_alternatif_input[$id_alternatif]) ? floatval($bobot_alternatif_input[$id_alternatif]) : 0;

            $query_insert_bobot_alt = "INSERT INTO bobot_alternatif (id_kriteria, id_alternatif, bobot) VALUES (?, ?, ?)";
            $stmt_bobot_alt = mysqli_prepare($conn, $query_insert_bobot_alt);
            if (!$stmt_bobot_alt) {
                throw new Exception("Gagal menyiapkan query insert bobot_alternatif: " . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt_bobot_alt, "iid", $id_kriteria_baru, $id_alternatif, $nilai_bobot);
            mysqli_stmt_execute($stmt_bobot_alt);
            mysqli_stmt_close($stmt_bobot_alt);

            $current_sum_for_modal += $nilai_bobot;
        }

        mysqli_commit($conn);
       // Langkah 1: Ambil total semua bobot kriteria
// Langkah 1: Ambil total semua bobot kriteria
$query_total_all = "SELECT SUM(total_bobot) FROM (
    SELECT SUM(b.bobot) AS total_bobot 
    FROM kriteria k
    JOIN bobot_alternatif b ON k.id_kriteria = b.id_kriteria
    GROUP BY k.id_kriteria
) AS all_kriteria_sum";

$result_total = mysqli_query($conn, $query_total_all);
$row_total = mysqli_fetch_row($result_total);
$total_semua_bobot = $row_total[0] ?? 1;

// Langkah 2: Hitung normalisasi setiap kriteria
$query_kriteria = "
    SELECT k.id_kriteria, SUM(b.bobot) AS total_bobot_kriteria 
    FROM kriteria k
    JOIN bobot_alternatif b ON k.id_kriteria = b.id_kriteria
    GROUP BY k.id_kriteria
";

$result_kriteria = mysqli_query($conn, $query_kriteria);

while ($row = mysqli_fetch_assoc($result_kriteria)) {
    $id_kriteria = $row['id_kriteria'];
    $bobot_kriteria = $row['total_bobot_kriteria'];

    $bobot_normal = ($bobot_kriteria / $total_semua_bobot) * 100;

    // Update tabel kriteria
    $update_normal = "UPDATE kriteria SET bobot_normalisasi = ? WHERE id_kriteria = ?";
    $stmt_normal = mysqli_prepare($conn, $update_normal);
    mysqli_stmt_bind_param($stmt_normal, "di", $bobot_normal, $id_kriteria);
    mysqli_stmt_execute($stmt_normal);
    mysqli_stmt_close($stmt_normal);
}

mysqli_free_result($result_kriteria);

$stmt_select = mysqli_prepare($conn, $query_select);
if ($stmt_select) {
    mysqli_stmt_bind_param($stmt_select, "i", $id_alt);
    mysqli_stmt_execute($stmt_select);
    $res = mysqli_stmt_get_result($stmt_select);

    while ($row = mysqli_fetch_assoc($res)) {
        $id_kri = $row['id_kriteria'];
        $bobot_normal = ($row['bobot'] / $total_bobot) * 100;

        $query_update = "UPDATE bobot_alternatif SET bobot = ? WHERE id_kriteria = ? AND id_alternatif = ?";
        $stmt_update = mysqli_prepare($conn, $query_update);
        mysqli_stmt_bind_param($stmt_update, "dii", $bobot_normal, $id_kri, $id_alt);
        mysqli_stmt_execute($stmt_update);
        mysqli_stmt_close($stmt_update);
    }

    mysqli_stmt_close($stmt_select); // ✅ Perbaikan: ini yang ditutup
}




        // Tampilkan modal sukses
        $sum_bobot_alternatif = $current_sum_for_modal;
        $showResultModal = true;
        $modalTitle = 'Sukses!';
        $modalMessage = 'Data kriteria berhasil ditambahkan.';

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<script>alert('Gagal menyimpan data: " . $e->getMessage() . "'); window.location.href='kriteria.php';</script>";
        exit;
    }
}


// --- Proses Edit (Simpan) ---
if (isset($_POST['edit'])) {
    $id_kriteria = $_POST['id_kriteria'];
    $kode = $_POST['kode'];
    $nama_kriteria = $_POST['nama_kriteria'];
    $jenis = $_POST['jenis'];
    // Ambil bobot alternatif untuk edit
    $bobot_alternatif_input_edit = isset($_POST['bobot_alternatif_edit']) ? $_POST['bobot_alternatif_edit'] : [];

    // Mulai transaksi untuk memastikan integritas data pada update
    mysqli_begin_transaction($conn);
    try {
        // UPDATE kriteria tanpa bobot global
        $query = "UPDATE kriteria SET kode=?, nama_kriteria=?, jenis=? WHERE id_kriteria=?";
        $stmt = mysqli_prepare($conn, $query);

        if (!$stmt) {
            throw new Exception("Gagal menyiapkan query update kriteria: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt, "sssi", $kode, $nama_kriteria, $jenis, $id_kriteria);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Hapus bobot lama dulu untuk kriteria ini
        $delete_old_bobot = "DELETE FROM bobot_alternatif WHERE id_kriteria = ?";
        $stmt_delete = mysqli_prepare($conn, $delete_old_bobot);
        if (!$stmt_delete) {
            throw new Exception("Gagal menyiapkan query delete bobot_alternatif: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt_delete, "i", $id_kriteria);
        mysqli_stmt_execute($stmt_delete);
        mysqli_stmt_close($stmt_delete);

        // Masukkan bobot baru per alternatif yang di-edit
        // Loop melalui $alternatif_for_input yang sudah difilter untuk menyimpan bobot
        foreach ($alternatif_for_input as $alt) {
            $id_alt = $alt['id_alternatif'];
            $nilai_bobot = isset($bobot_alternatif_input_edit[$id_alt]) ? floatval($bobot_alternatif_input_edit[$id_alt]) : 0;

            $query_alt_bobot_update = "INSERT INTO bobot_alternatif (id_kriteria, id_alternatif, bobot) VALUES (?, ?, ?)";
            $stmt_alt_bobot_update = mysqli_prepare($conn, $query_alt_bobot_update);
            if (!$stmt_alt_bobot_update) {
                throw new Exception("Gagal menyiapkan query insert bobot_alternatif (update): " . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt_alt_bobot_update, "iid", $id_kriteria, $id_alt, $nilai_bobot);
            mysqli_stmt_execute($stmt_alt_bobot_update);
            mysqli_stmt_close($stmt_alt_bobot_update);
        }

        mysqli_commit($conn); // Commit transaksi jika semua berhasil
        header("Location: kriteria.php");
        exit;
    } catch (Exception $e) {
        mysqli_rollback($conn); // Rollback transaksi jika ada kesalahan
        echo "<script>alert('Gagal memperbarui data: " . $e->getMessage() . "'); window.location.href='kriteria.php';</script>";
        exit;
    }
}

// --- Proses Ambil Data Edit ---
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id_kriteria = $_GET['edit'];
    
    // Ambil data kriteria (tanpa bobot global)
    $stmt = mysqli_prepare($conn, "SELECT id_kriteria, kode, nama_kriteria, jenis FROM kriteria WHERE id_kriteria = ?");
    mysqli_stmt_bind_param($stmt, "i", $id_kriteria);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($data) {
        $kode = $data['kode'];
        $nama_kriteria = $data['nama_kriteria'];
        $jenis = $data['jenis'];

        // Ambil bobot alternatif yang sudah ada untuk kriteria ini
        $stmt_bobot_alt = mysqli_prepare($conn, "SELECT id_alternatif, bobot FROM bobot_alternatif WHERE id_kriteria = ?");
        mysqli_stmt_bind_param($stmt_bobot_alt, "i", $id_kriteria);
        mysqli_stmt_execute($stmt_bobot_alt);
        $result_bobot_alt = mysqli_stmt_get_result($stmt_bobot_alt);
        while ($row_bobot_alt = mysqli_fetch_assoc($result_bobot_alt)) {
            $bobot_alternatif_values[$row_bobot_alt['id_alternatif']] = $row_bobot_alt['bobot'];
        }
        mysqli_stmt_close($stmt_bobot_alt);

    } else {
        echo "<script>alert('Data kriteria tidak ditemukan.'); window.location.href='kriteria.php';</script>";
        exit;
    }
}

// --- Proses Hapus ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_begin_transaction($conn);
    try {
        $query_delete_bobot_alt = "DELETE FROM bobot_alternatif WHERE id_kriteria = ?";
        $stmt_delete_bobot_alt = mysqli_prepare($conn, $query_delete_bobot_alt);
        if ($stmt_delete_bobot_alt) {
            mysqli_stmt_bind_param($stmt_delete_bobot_alt, "i", $id);
            mysqli_stmt_execute($stmt_delete_bobot_alt);
            mysqli_stmt_close($stmt_delete_bobot_alt);
        } else {
            throw new Exception("Gagal menyiapkan query hapus bobot_alternatif: " . mysqli_error($conn));
        }

        $query_delete_kriteria = "DELETE FROM kriteria WHERE id_kriteria = ?";
        $stmt_delete_kriteria = mysqli_prepare($conn, $query_delete_kriteria);
        if ($stmt_delete_kriteria) {
            mysqli_stmt_bind_param($stmt_delete_kriteria, "i", $id);
            mysqli_stmt_execute($stmt_delete_kriteria);
            mysqli_stmt_close($stmt_delete_kriteria);
        } else {
            throw new Exception("Gagal menyiapkan query hapus kriteria: " . mysqli_error($conn));
        }

        mysqli_commit($conn);
        header("Location: kriteria.php");
        exit;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<script>alert('Gagal menghapus data: " . $e->getMessage() . "'); window.location.href='kriteria.php';</script>";
        exit;
    }
}

// Ambil Semua Data Kriteria (untuk tabel di bawah)
$kriteria = mysqli_query($conn, "
    SELECT k.id_kriteria, k.kode, k.nama_kriteria, k.jenis,
           COALESCE(SUM(b.bobot), 0) AS total_bobot_alternatif
    FROM kriteria k
    LEFT JOIN bobot_alternatif b ON k.id_kriteria = b.id_kriteria
    GROUP BY k.id_kriteria, k.kode, k.nama_kriteria, k.jenis
    ORDER BY k.id_kriteria ASC
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="logo.png" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kriteria</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
/* ... (CSS Anda yang sudah ada, tidak ada perubahan di sini) ... */
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
.dark-mode .table {
    background-color: #333;
    color: white;
}
    .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            margin-top: 50px;

.dark-mode .table th,
.dark-mode .table td {
    background-color: #444;
    color: white;
    border-color: #555;
}
    </style>
</head>
<body class="<?php echo $themeClass; ?>">
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
                    <li class="nav-item"><a class="nav-link text-white" href="about.php">Tentang Kami</a></li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link text-white" href="kriteria.php">Kriteria</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="alternatif.php">Alternatif</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="matakuliah.php">Mata Kuliah</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="Kri.php">Bobot  Alternatif</a></li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
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
                        <form method="POST" action="kri.php">

                            <button class="btn btn-outline-light" name="theme-toggle"><i class="fas fa-moon"></i></button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Data Kriteria</h2>
    <button class="btn btn-success mb-1" onclick="toggleForm()" id="toggleButton">
        <?= $edit_mode ? 'Sembunyikan Form' : 'Tambah Kriteria' ?>
    </button>

    <div class="card mb-4" id="formCard" style="display: <?= $edit_mode ? 'block' : 'none' ?>;">
        <div class="card-body">
            
            <form method="POST">
                <input type="hidden" name="id_kriteria" value="<?= htmlspecialchars($id_kriteria) ?>">
                <div class="form-group mb-2">
                    <label for="kode">Kode</label>
                    <input type="text" name="kode" id="kode" class="form-control" placeholder="Kode Kriteria" value="<?= htmlspecialchars($kode) ?>" required>
                </div>
                <div class="form-group mb-2">
                    <label for="nama_kriteria">Nama Kriteria</label>
                    <input type="text" name="nama_kriteria" id="nama_kriteria" class="form-control" placeholder="Nama Kriteria" value="<?= htmlspecialchars($nama_kriteria) ?>" required>
                </div>
                
                <div class="form-group mb-2">
                    <label for="jenis">Jenis</label>
                    <select name="jenis" id="jenis" class="form-control" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Benefit" <?= $jenis == 'Benefit' ? 'selected' : '' ?>>Benefit</option>
                        <option value="Cost" <?= $jenis == 'Cost' ? 'selected' : '' ?>>Cost</option>
                    </select>
                </div>

                <h5 class="mt-4">Bobot Kriteria per Alternatif</h5>
                <small class="text-muted mb-3 d-block">Isi bobot untuk setiap alternatif sesuai dengan kriteria ini.</small>
                <?php if (empty($alternatif_for_input)): // Cek $alternatif_for_input yang sudah difilter di atas ?>
                    <p class="text-warning">Belum ada data alternatif yang sesuai. Harap pastikan alternatif "Rekayasa Perangkat Lunak", "Data Science", dan "Jaringan Komputer" ada di database Anda.</p>
                <?php else: ?>
                    <?php 
                    foreach ($alternatif_for_input as $alt): // Gunakan array $alternatif_for_input yang sudah difilter
                    ?>
                        <div class="form-group mb-2">
                            <label>Bobot untuk <?= htmlspecialchars($alt['nama']); ?></label>
                            <input type="number" step="0.01" 
                                name="<?= $edit_mode ? 'bobot_alternatif_edit' : 'bobot_alternatif' ?>[<?= $alt['id_alternatif']; ?>]" 
                                class="form-control" 
                                placeholder="Masukkan bobot" 
                                value="<?= isset($bobot_alternatif_values[$alt['id_alternatif']]) ? htmlspecialchars($bobot_alternatif_values[$alt['id_alternatif']]) : '' ?>" 
                                required>
                        </div>
                    <?php 
                    endforeach; 
                    ?>
                <?php endif; ?>
                
                <button type="submit" name="<?= $edit_mode ? 'edit' : 'tambah' ?>" class="btn btn-primary mt-3">
                    <?= $edit_mode ? 'Simpan Perubahan' : 'Tambah Kriteria & Hitung Bobot' ?>
                </button>
                <button type="button" class="btn btn-secondary mt-3 ms-2" onclick="resetForm()">Reset</button>
            </form>
        </div>
    </div>

    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel"><?= htmlspecialchars($modalTitle) ?></h5>
                    </div>
                <div class="modal-body">
                    <p><?= htmlspecialchars($modalMessage) ?></p>
                    <p class="text-muted"><small>Jendela akan tertutup otomatis dalam <span id="countdown">5</span> detik.</small></p>
                </div>
                </div>
        </div>
    </div>

    <table class="table table-bordered table-striped <?= $themeClass ? 'table-dark' : '' ?> mt-4">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Total Bobot Alternatif</th>
                <th>Jenis</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($kriteria)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_kriteria']); ?></td>
                    <td><?= htmlspecialchars($row['kode']); ?></td>
                    <td><?= htmlspecialchars($row['nama_kriteria']); ?></td>
                    <td><?= htmlspecialchars($row['total_bobot_alternatif']); ?></td>
                    <td><?= htmlspecialchars($row['jenis']); ?></td>
                    <td>
                        <a href="?edit=<?= $row['id_kriteria']; ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="?hapus=<?= $row['id_kriteria']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?');">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 DSS-TI | Sistem Pendukung Keputusan untuk Pemilihan Peminatan</p>
    </footer>
<script>
function toggleForm() {
    const form = document.getElementById('formCard');
    const btn = document.getElementById('toggleButton');
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
        btn.innerText = 'Sembunyikan Form';
    } else {
        form.style.display = 'none';
        btn.innerText = 'Tambah Kriteria';
    }
}

function resetForm() {
    window.location.href = 'kriteria.php';
}

// Skrip untuk menampilkan modal notifikasi
<?php if ($showResultModal): // Cukup cek showResultModal ?>
    document.addEventListener('DOMContentLoaded', function() {
        var notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
        notificationModal.show();

        // Countdown for automatic close
        var countdownElement = document.getElementById('countdown');
        var seconds = 5;
        countdownElement.innerText = seconds;

        var countdownInterval = setInterval(function() {
            seconds--;
            countdownElement.innerText = seconds;
            if (seconds <= 0) {
                clearInterval(countdownInterval);
                notificationModal.hide(); // Sembunyikan modal
                // Setelah modal tertutup, refresh halaman
                window.location.href = 'kriteria.php'; 
            }
        }, 1000); // Setiap 1 detik
    });
<?php endif; ?>
</script>
<div class="modal fade" id="userProfileModal" tabindex="-1" aria-labelledby="userProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userProfileModalLabel">Profil Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Nama:</strong> <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Belum Login'; ?></p>
                <p><strong>Email:</strong> <?php echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : 'Belum Login'; ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
