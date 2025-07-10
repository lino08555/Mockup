<?php
session_start();
include 'koneksi.php';

// Ambil ID dari URL
if (!isset($_GET['id'])) {
    header('Location: kri.php');
    exit;
}

$id = intval($_GET['id']);

// Ambil data berdasarkan ID
$query = "SELECT * FROM bobot_alternatif WHERE id = $id";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    echo "Data tidak ditemukan.";
    exit;
}

$data = $result->fetch_assoc();

// Proses update data jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_alternatif = $_POST['id_alternatif'];
    $id_kriteria = $_POST['id_kriteria'];
    $bobot = $_POST['bobot'];
    $keanggotaan = $_POST['keanggotaan'];
    $jmlh_bobot = $bobot * $keanggotaan; // perhitungan jumlah bobot
    $normalisasi = $_POST['normalisasi']; // bisa disesuaikan otomatis jika ada prosesnya

    $update = $conn->prepare("UPDATE bobot_alternatif SET id_alternatif=?, id_kriteria=?, bobot=?, keanggotaan=?, jmlh_bobot_kriteria=?, normalisasi_bobot=? WHERE id=?");
    $update->bind_param("iiddddi", $id_alternatif, $id_kriteria, $bobot, $keanggotaan, $jmlh_bobot, $normalisasi, $id);

    if ($update->execute()) {
        header("Location: kri.php?msg=updated");
        exit;
    } else {
        echo "Gagal memperbarui data.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Bobot Alternatif</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h2>Edit Bobot Alternatif</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="id_alternatif" class="form-label">Alternatif</label>
            <select name="id_alternatif" class="form-control" required>
                <option value="">-- Pilih Alternatif --</option>
                <?php
                $alt = $conn->query("SELECT * FROM alternatif");
                while ($a = $alt->fetch_assoc()) {
                    $selected = ($a['id_alternatif'] == $data['id_alternatif']) ? 'selected' : '';
                    echo "<option value='{$a['id_alternatif']}' $selected>{$a['nama']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="id_kriteria" class="form-label">Kriteria</label>
            <select name="id_kriteria" class="form-control" required>
                <option value="">-- Pilih Kriteria --</option>
                <?php
                $kri = $conn->query("SELECT * FROM kriteria");
                while ($k = $kri->fetch_assoc()) {
                    $selected = ($k['id_kriteria'] == $data['id_kriteria']) ? 'selected' : '';
                    echo "<option value='{$k['id_kriteria']}' $selected>{$k['nama_kriteria']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="bobot" class="form-label">Bobot</label>
            <input type="number" step="0.01" name="bobot" class="form-control" value="<?= $data['bobot'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="keanggotaan" class="form-label">Keanggotaan</label>
            <input type="number" step="0.01" name="keanggotaan" class="form-control" value="<?= $data['keanggotaan'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="normalisasi" class="form-label">Normalisasi</label>
            <input type="number" step="0.0001" name="normalisasi" class="form-control" value="<?= $data['normalisasi_bobot'] ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="kri.php" class="btn btn-secondary">Kembali</a>
    </form>
</body>
</html>
