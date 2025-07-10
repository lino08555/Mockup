<?php
session_start();
include 'koneksi.php';

$alternatif = $conn->query("SELECT * FROM alternatif");
$kriteria = $conn->query("SELECT * FROM kriteria");

function hitungKeanggotaan($total_bobot) {
    if ($total_bobot >= 1 && $total_bobot <= 10) return 1;
    elseif ($total_bobot >= 11 && $total_bobot <= 20) return 2;
    elseif ($total_bobot >= 21 && $total_bobot <= 30) return 3;
    else return 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_alternatif = $_POST['id_alternatif'];
    $id_kriteria = $_POST['id_kriteria'];
    $bobot = floatval($_POST['bobot']);

    // Simpan data awal
    $stmt = $conn->prepare("INSERT INTO bobot_alternatif (id_kriteria, id_alternatif, bobot, keanggotaan) VALUES (?, ?, ?, 0)");
    $stmt->bind_param("iidi", $id_kriteria, $id_alternatif, $bobot, $dummy);
    $stmt->execute();

    // Step 1: Hitung ulang jumlah bobot per kriteria & keanggotaan per kriteria
    $total_keanggotaan = 0;
    $data_kriteria = [];

    $kriteria_all = $conn->query("SELECT * FROM kriteria");
    while ($k = $kriteria_all->fetch_assoc()) {
        $id_kri = $k['id_kriteria'];
        
        $q = $conn->query("SELECT SUM(bobot) AS total_bobot FROM bobot_alternatif WHERE id_kriteria = $id_kri");
        $row = $q->fetch_assoc();
        $total_bobot = $row['total_bobot'] ?? 0;
        
        $keanggotaan = hitungKeanggotaan($total_bobot);
        $total_keanggotaan += $keanggotaan;

        $data_kriteria[$id_kri] = [
            'total_bobot' => $total_bobot,
            'keanggotaan' => $keanggotaan
        ];
    }

    // Step 2: Update semua data bobot_alternatif
    $ambil = $conn->query("SELECT * FROM bobot_alternatif");
    while ($d = $ambil->fetch_assoc()) {
        $id = $d['id'];
        $id_kri = $d['id_kriteria'];

        $total_bobot = $data_kriteria[$id_kri]['total_bobot'];
        $keanggotaan = $data_kriteria[$id_kri]['keanggotaan'];

        $normalisasi = ($total_keanggotaan > 0) ? ($keanggotaan / $total_keanggotaan) : 0;

        $update = $conn->prepare("UPDATE bobot_alternatif SET jmlh_bobot_kriteria = ?, keanggotaan = ?, normalisasi_bobot = ? WHERE id = ?");
        $update->bind_param("dddi", $total_bobot, $keanggotaan, $normalisasi, $id);
        $update->execute();
    }

    header("Location: kri.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Bobot Alternatif</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h3>Tambah Bobot Alternatif</h3>
    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label for="id_alternatif" class="form-label">Pilih Alternatif</label>
            <select name="id_alternatif" id="id_alternatif" class="form-select" required>
                <option value="">-- Pilih Alternatif --</option>
                <?php while ($a = $alternatif->fetch_assoc()): ?>
                    <option value="<?= $a['id_alternatif'] ?>"><?= $a['nama'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label for="id_kriteria" class="form-label">Pilih Kriteria</label>
            <select name="id_kriteria" id="id_kriteria" class="form-select" required>
                <option value="">-- Pilih Kriteria --</option>
                <?php while ($k = $kriteria->fetch_assoc()): ?>
                    <option value="<?= $k['id_kriteria'] ?>"><?= $k['nama_kriteria'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label for="bobot" class="form-label">Nilai Bobot (1–30)</label>
            <input type="number" name="bobot" id="bobot" class="form-control" min="1" max="30" required>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="kri.php" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</body>
</html>
