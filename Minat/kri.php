<?php
session_start();

// Menentukan apakah pengguna sudah login
$userLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == true;

// Menyimpan status tema jika pengguna mengubahnya
if (isset($_POST['theme-toggle'])) {
    // Mengubah tema dari 'dark' ke 'light' atau sebaliknya
    $_SESSION['theme'] = ($_SESSION['theme'] == 'dark') ? 'light' : 'dark';
} elseif (!isset($_SESSION['theme'])) {
    // Inisialisasi tema default jika belum ada
    $_SESSION['theme'] = 'light';
}

// Mengatur tema default berdasarkan preferensi pengguna
$themeClass = $_SESSION['theme'] == 'dark' ? 'bg-dark text-light dark-mode' : '';
include 'koneksi.php';

// Hitung total bobot & keanggotaan per kriteria
$data_kriteria = [];
$total_keanggotaan = 0;

$kriteria_all = $conn->query("SELECT * FROM kriteria");
while ($k = $kriteria_all->fetch_assoc()) {
    $id_kri = $k['id_kriteria'];
    
    $q = $conn->query("SELECT SUM(bobot) AS total_bobot FROM bobot_alternatif WHERE id_kriteria = $id_kri");
    $row = $q->fetch_assoc();
    $total_bobot = $row['total_bobot'] ?? 0;

    // Hitung keanggotaan
    if ($total_bobot >= 1 && $total_bobot <= 10) $keanggotaan = 1;
    elseif ($total_bobot >= 11 && $total_bobot <= 20) $keanggotaan = 2;
    elseif ($total_bobot >= 21 && $total_bobot <= 30) $keanggotaan = 3;
    else $keanggotaan = 0;

    $total_keanggotaan += $keanggotaan;

    $data_kriteria[$id_kri] = [
        'total_bobot' => $total_bobot,
        'keanggotaan' => $keanggotaan
    ];
}

// Update semua data bobot_alternatif
$ambil = $conn->query("SELECT * FROM bobot_alternatif");
while ($d = $ambil->fetch_assoc()) {
    $id = $d['id'];
    $id_kri = $d['id_kriteria'];

    $total_bobot = $data_kriteria[$id_kri]['total_bobot'];
    $keanggotaan = $data_kriteria[$id_kri]['keanggotaan'];
    $normalisasi = ($total_keanggotaan > 0) ? ($keanggotaan / $total_keanggotaan) : 0;

    $conn->query("UPDATE bobot_alternatif SET jmlh_bobot_kriteria = $total_bobot, keanggotaan = $keanggotaan, normalisasi_bobot = $normalisasi WHERE id = $id");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Data Bobot Alternatif</title>
<link rel="icon" href="logo.png" type="image/x-icon">
<!-- Bootstrap 5.3 CSS CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
.hero video {
    width: 100%;
    max-width: 400px; /* Batas maksimum lebar video */
    height: auto;
    border-radius: 10px;
    order: 2; /* Menjaga urutan seperti sebelumnya */
    object-fit: cover; /* Agar video mengisi area tanpa distorsi */
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


<div class="container">
    <h2 class="mt-4">Data Bobot Alternatif</h2>

    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-3">
            <input type="text" name="keyword" class="form-control" placeholder="Cari nama..." value="<?= $_GET['keyword'] ?? '' ?>">
        </div>
        <div class="col-md-3">
            <select name="filter_alternatif" class="form-control">
                <option value="">-- Filter Alternatif --</option>
                <?php
                $alt = $conn->query("SELECT * FROM alternatif");
                while ($a = $alt->fetch_assoc()): ?>
                    <option value="<?= $a['id_alternatif'] ?>" <?= ($_GET['filter_alternatif'] ?? '') == $a['id_alternatif'] ? 'selected' : '' ?>><?= $a['nama'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="filter_kriteria" class="form-control">
                <option value="">-- Filter Kriteria --</option>
                <?php
                $kr = $conn->query("SELECT * FROM kriteria");
                while ($k = $kr->fetch_assoc()): ?>
                    <option value="<?= $k['id_kriteria'] ?>" <?= ($_GET['filter_kriteria'] ?? '') == $k['id_kriteria'] ? 'selected' : '' ?>><?= $k['nama_kriteria'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="kri.php" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <table class="table table-bordered table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Alternatif</th>
                <th>Kriteria</th>
                <th>Bobot</th>
                <th>Jumlah Bobot</th>
                <th>Keanggotaan</th>
                <th>Normalisasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
       <tbody>
<?php
$where = [];

if (!empty($_GET['keyword'])) {
    $kw = $conn->real_escape_string($_GET['keyword']);
    $where[] = "(a.nama LIKE '%$kw%' OR k.nama_kriteria LIKE '%$kw%')";
}
if (!empty($_GET['filter_alternatif'])) {
    $where[] = "ba.id_alternatif = " . intval($_GET['filter_alternatif']);
}
if (!empty($_GET['filter_kriteria'])) {
    $where[] = "ba.id_kriteria = " . intval($_GET['filter_kriteria']);
}

$sql = "SELECT ba.*, a.nama AS nama_alternatif, k.nama_kriteria FROM bobot_alternatif ba
        JOIN alternatif a ON ba.id_alternatif = a.id_alternatif
        JOIN kriteria k ON ba.id_kriteria = k.id_kriteria";

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY ba.id ASC";

$result = $conn->query($sql);
$no = 1;
$total_keanggotaan_all = 0;
$total_normalisasi_all = 0;

if ($result->num_rows > 0):
    while ($row = $result->fetch_assoc()):
        $total_keanggotaan_all += $row['keanggotaan'];
        $total_normalisasi_all += $row['normalisasi_bobot'];
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama_alternatif']) ?></td>
            <td><?= htmlspecialchars($row['nama_kriteria']) ?></td>
            <td><?= $row['bobot'] ?></td>
            <td><?= $row['jmlh_bobot_kriteria'] ?></td>
            <td><?= $row['keanggotaan'] ?></td>
            <td><?= $row['normalisasi_bobot'] ?></td>
            <td>
                <a href="edt.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="del.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">Hapus</a>
            </td>
        </tr>
    <?php endwhile; else: ?>
    <tr><td colspan="8" class="text-center">Data tidak ditemukan.</td></tr>
<?php endif; ?>
</tbody>

<tfoot class="table-light fw-bold">
    <tr>
        <td colspan="5" class="text-end">Total</td>
        <td><?= $total_keanggotaan_all ?></td>
        <td><?= number_format($total_normalisasi_all, 5) ?></td>
        <td>-</td>
    </tr>
</tfoot>

    </table>
    <?php
// Hitung jumlah alternatif unik
$jumlah_alternatif_query = $conn->query("SELECT COUNT(DISTINCT id_alternatif) AS total_alt FROM bobot_alternatif");
$jumlah_alternatif = $jumlah_alternatif_query->fetch_assoc()['total_alt'] ?? 1;

// Hitung jumlah kriteria unik
$jumlah_kriteria_query = $conn->query("SELECT COUNT(DISTINCT id_kriteria) AS total_kri FROM bobot_alternatif");
$jumlah_kriteria = $jumlah_kriteria_query->fetch_assoc()['total_kri'] ?? 1;

// Hitung total dibagi 3 (jumlah alternatif tergantung inputan)
$rata_keanggotaan = $total_keanggotaan_all / $jumlah_alternatif;
$rata_normalisasi = $total_normalisasi_all / $jumlah_alternatif;
?>

</div>
<div class="container mt-5">
    <h4>Ringkasan Hasil</h4>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Jumlah Kriteria</th>
                <th>Jumlah Alternatif</th>
                <th>Total Keanggotaan</th>
                <th>Total Normalisasi</th>
                <th>Rata-rata Keanggotaan (÷ Alternatif)</th>
                <th>Rata-rata Normalisasi (÷ Alternatif)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $jumlah_kriteria ?></td>
                <td><?= $jumlah_alternatif ?></td>
                <td><?= $total_keanggotaan_all ?></td>
                <td><?= number_format($total_normalisasi_all, 5) ?></td>
                <td><?= number_format($rata_keanggotaan, 2) ?></td>
                <td><?= number_format($rata_normalisasi, 5) ?></td>
            </tr>
        </tbody>
    </table>
</div>

<footer class="footer">
    <p>&copy; 2025 DSS-TI | Sistem Pendukung Keputusan untuk Pemilihan Peminatan</p>
</footer>
</body>
</html>