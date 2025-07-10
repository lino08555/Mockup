<?php
include 'koneksi.php';

// Pastikan form dikirim
if (!isset($_POST['kriteria'], $_POST['kuis'])) {
    die('Akses tidak sah – data belum dikirim.');
}

$nilai_kriteria = array_map('floatval', $_POST['kriteria']);   // 14 nilai (0 ‑100)
$nilai_kuis   = array_map('floatval', $_POST['kuis']);     // 15 jawaban kuis (skala 1 ‑5)

/* =============================================================
   1. KONVERSI NILAI MATA KULIAH -> RATING 1–4
   =============================================================*/
function konversi_rating($nilai) {
    if ($nilai >= 80) return 4;
    if ($nilai >= 70) return 3;
    if ($nilai >= 60) return 2;
    return 1;
}
foreach ($nilai_kriteria as &$v) {
    $v = konversi_rating($v);
}
unset($v);

/* =============================================================
   2. PROSES KUIS (15 soal, 3 kelompok alternatif)
   =============================================================*/
$chunks = array_chunk($nilai_kuis, 5); // [[Q1 ‑Q5],[Q6 ‑Q10],[Q11 ‑Q15]]

/* =============================================================
   3. AMBIL DATA KRITERIA DAN BOBOT TERNORMALISASI
   =============================================================*/
$bobot_kriteria = [];
$jenis_kriteria = [];
$nama_kriteria  = [];
$total_bobot    = 0;

$q_krit = mysqli_query($conn, "
    SELECT  k.id_kriteria,
            k.jenis,
            k.kode,
            COALESCE(MAX(ba.normalisasi_bobot), 0) AS bobot
    FROM    kriteria k
    LEFT JOIN bobot_alternatif ba ON ba.id_kriteria = k.id_kriteria
    GROUP BY k.id_kriteria
    ORDER BY k.id_kriteria
");

while ($row = mysqli_fetch_assoc($q_krit)) {
    $id                        = (int)$row['id_kriteria'];
    $bobot_kriteria[$id]       = (float)$row['bobot'];
    $jenis_kriteria[$id]       = $row['jenis'];
    $nama_kriteria[$id]        = $row['kode'];
    $total_bobot              += $row['bobot'];
}

if (empty($nama_kriteria)) {
    die("Data kriteria belum tersedia. Silakan isi bobot kriteria terlebih dahulu.");
}

/* =============================================================
   4. BENTUK RATING PER ALTERNATIF
   =============================================================*/
$q_alt = mysqli_query($conn, "SELECT id_alternatif, nama FROM alternatif ORDER BY id_alternatif");
$alternatif       = [];
$nilai_alternatif = [];
$i_chunk          = 0;

while ($alt = mysqli_fetch_assoc($q_alt)) {
    $alternatif[] = $alt;
    $id_alt       = $alt['id_alternatif'];

    $nilai_alternatif[$id_alt] = $nilai_kriteria;
    $jumlah_kuis = isset($chunks[$i_chunk]) ? array_sum($chunks[$i_chunk]) : 0;
    $nilai_alternatif[$id_alt][15] = $jumlah_kuis;
    $i_chunk++;
}

/* =============================================================
   4B. RATING KECOCOKAN (nilai x bobot alternatif / 10)
   =============================================================*/
$bobotAlt = [];
$qBa = mysqli_query($conn, "SELECT id_alternatif, id_kriteria, bobot, normalisasi_bobot FROM bobot_alternatif");
while ($r = mysqli_fetch_assoc($qBa)) {
    $bobotAlt[(int)$r['id_alternatif']][(int)$r['id_kriteria']] = [
        'bobot' => (float)$r['bobot'],
        'normalisasi_bobot' => (float)$r['normalisasi_bobot']
    ];
}

$rating_kecocokan = [];
foreach ($nilai_alternatif as $idAlt => $baris) {
    foreach ($baris as $idKrit => $nilaiInput) {
        $bobot = $bobotAlt[$idAlt][$idKrit]['bobot'] ?? 0;
        $rating_kecocokan[$idAlt][$idKrit] = ($nilaiInput * $bobot) / 10;
    }
}

/* =============================================================
   5. NORMALISASI SAW (berdasarkan rating kecocokan)
   =============================================================*/
$normalisasi = [];
foreach ($bobot_kriteria as $id_k => $w) {
    $kolom = array_column($rating_kecocokan, $id_k);
    $max   = max($kolom);
    $min   = min(array_filter($kolom));

    foreach ($rating_kecocokan as $id_alt => $row) {
        $nilai = $row[$id_k] ?? 0;
        $normalisasi[$id_alt][$id_k] = ($jenis_kriteria[$id_k] === 'cost')
            ? ($nilai > 0 ? $min / $nilai : 0)
            : ($max > 0 ? $nilai / $max : 0);
    }
}

/* =============================================================
   6. NILAI TERBOBOT & SKOR AKHIR
   =============================================================*/
$nilai_terbobot = [];
$hasil_saw = [];

foreach ($normalisasi as $id_alt => $row) {
    $total = 0;
    foreach ($row as $id_k => $r) {
        $n_bobot = $bobotAlt[$id_alt][$id_k]['normalisasi_bobot'] ?? 0;
        $v = $r * $n_bobot;
        $nilai_terbobot[$id_alt][$id_k] = $v;
        $total += $v;
    }
    $hasil_saw[$id_alt] = $total;
}

arsort($hasil_saw);
$rekomendasi_id = array_key_first($hasil_saw);
$rekomendasi_nama = '';

foreach ($alternatif as $alt) {
    if ($alt['id_alternatif'] == $rekomendasi_id) {
        $rekomendasi_nama = $alt['nama'];
        break;
    }
}
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="logo.png" type="image/x-icon">
    <meta charset="UTF-8">
    <title>Hasil Perhitungan SAW</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body{background:#f8f9fa}.container{margin:40px auto}.table thead{background:maroon;color:#fff}
        .btn-maroon{background:linear-gradient(45deg,maroon,#800000);color:#fff;border:none;font-weight:600;padding:10px 24px;border-radius:8px;transition:.3s}
        .btn-maroon:hover{transform:scale(1.05);background:linear-gradient(45deg,#990000,#660000)}
    </style>
</head>
<body>
<div class="container">
    <h2 class="mb-4">Hasil Perhitungan SAW</h2>

    <p><strong>Keterangan Rating Nilai Mata Kuliah:</strong></p>
    <ul>
        <li>80 – 100 → A → Rating: 4</li>
        <li>70 – 79 → B → Rating: 3</li>
        <li>60 – 69 → C → Rating: 2</li>
        <li>50 – 59 → D → Rating: 1</li>
    </ul>

    <!-- 1. Rating Kecocokan -->
    <h5>1. Rating Kecocokan (Nilai Alternatif)</h5>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Alternatif</th>
            <?php foreach ($nama_kriteria as $nk) : ?>
                <th><?= htmlspecialchars($nk) ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($nilai_alternatif as $id_alt => $row) : ?>
            <tr>
                <td>
                    <?= htmlspecialchars($alternatif[array_search($id_alt, array_column($alternatif, 'id_alternatif'))]['nama']) ?>
                </td>
                <?php foreach (array_keys($nama_kriteria) as $id_k) : ?>
                    <td><?= $row[$id_k] ?? '-' ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<h5>1B. Rating Kecocokan (× bobot alternatif ÷ 10)</h5>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Alternatif</th>
            <?php foreach ($nama_kriteria as $nk) : ?>
                <th><?= htmlspecialchars($nk) ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rating_kecocokan as $id_alt => $row) : ?>
            <tr>
                <td>
                    <?= htmlspecialchars($alternatif[array_search($id_alt, array_column($alternatif, 'id_alternatif'))]['nama']) ?>
                </td>
                <?php foreach (array_keys($nama_kriteria) as $id_k) : ?>
                    <td><?= isset($row[$id_k]) ? round($row[$id_k], 4) : '-' ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


    <!-- 2. Normalisasi -->
    <h5>2. Normalisasi</h5>
    <table class="table table-bordered table-striped">
        <thead><tr><th>Alternatif</th><?php foreach($nama_kriteria as $nk) echo "<th>$nk</th>";?></tr></thead>
        <tbody>
        <?php foreach($normalisasi as $id_alt=>$row):?>
            <tr>
                <td><?=htmlspecialchars($alternatif[array_search($id_alt,array_column($alternatif,'id_alternatif'))]['nama'])?></td>
                <?php foreach($nama_kriteria as $id_k=>$nk) echo '<td>'.round($row[$id_k],4).'</td>'; ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>

<!-- 3. Nilai Terbobot -->
<h5>3. Nilai Terbobot & Total</h5>
<table class="table table-bordered table-striped">
    <thead><tr><th>Alternatif</th><?php foreach($nama_kriteria as $nk) echo "<th>$nk</th>";?><th>Total</th></tr></thead>
    <tbody>
    <?php foreach($nilai_terbobot as $id_alt=>$row): $tot=0;?>
        <tr>
            <td><?=htmlspecialchars($alternatif[array_search($id_alt,array_column($alternatif,'id_alternatif'))]['nama'])?></td>
            <?php foreach($nama_kriteria as $id_k=>$nk){
                $val = $row[$id_k];
                echo "<td>".round($val, 4)."</td>";
            }?>
            <td><strong><?=number_format($hasil_saw[$id_alt], 4)?></strong></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>


    <!-- 4. Ranking -->
    <h5>4. Ranking Akhir</h5>
    <table class="table table-bordered table-striped">
        <thead><tr><th>Alternatif</th><th>Skor Akhir</th></tr></thead>
        <tbody>
        <?php foreach($hasil_saw as $id_alt=>$skor):?>
            <tr>
                <td><?=htmlspecialchars($alternatif[array_search($id_alt,array_column($alternatif,'id_alternatif'))]['nama'])?></td>
                <td><?=round($skor,4)?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>

    <div class="alert alert-success" role="alert">
        <strong>Rekomendasi:</strong> Peminatan terbaik adalah <strong><?=htmlspecialchars($rekomendasi_nama)?></strong> dengan skor <strong><?=round($hasil_saw[$rekomendasi_id],4)?></strong>.
    </div>

    <div class="text-center"><a href="index.php" class="btn btn-maroon">Kembali ke Beranda</a></div>
</div>
</body>
</html>
